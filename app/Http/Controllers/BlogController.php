<?php

namespace App\Http\Controllers;

use App\Jobs\BlogIndexData;
use App\Post;

// 添加rss服务
use App\Services\RssFeed;
use App\Services\SiteMap;
use App\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller {
	public function index(Request $request) {
		$tag = $request->get('tag');
		$data = $this->dispatch(new BlogIndexData($tag));
		$layout = $tag ? Tag::layout($tag) : 'blog.layouts.index';

		return view($layout, $data);
	}

	public function showPost($slug, Request $request) {
		$post = Post::with('tags')->whereSlug($slug)->firstOrFail();
		$tag = $request->get('tag');
		if ($tag) {
			$tag = Tag::whereTag($tag)->firstOrFail();
		}
		$url = env('APP_URL');
		// return view($post->layout, compact('post', 'tag'));
		return view($post->layout, compact('post', 'tag', 'slug', 'url'));
	}

	public function rss(RssFeed $feed) {
		$rss = $feed->getRSS();

		return response($rss)
			->header('Content-type', 'application/rss+xml');
	}

	// 同时在控制器中新增这个方法
	public function siteMap(SiteMap $siteMap) {
		$map = $siteMap->getSiteMap();

		return response($map)
			->header('Content-type', 'text/xml');
	}
}