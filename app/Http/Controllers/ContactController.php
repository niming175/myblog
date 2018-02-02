<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller {
	/**
	 * 显示表单
	 *
	 * @return View
	 */
	public function showForm() {
		return view('blog.contact');
	}

	/**
	 * Email the contact request
	 *
	 * @param ContactMeRequest $request
	 * @return Redirect
	 */
	public function sendContactInfo(ContactMeRequest $request) {
		$data = $request->only('name', 'email', 'phone');
		$data['messageLines'] = explode("\n", $request->get('message'));

		// Mail::send 等待邮件发送； Mail::queue 推进队列
		Mail::queue('email.contact', $data, function ($message) use ($data) {
			$message
				->to(config('blog.author_email')) // 发送给作者
				->replyTo($data['email']) // 用户邮件, 这里并不会转发给用户，暂时不知道时干嘛的
				->subject('Blog Contact Form: ' . $data['name']);
		});

		return back()
			->withSuccess("Thank you for your message. It has been sent.");
	}
}