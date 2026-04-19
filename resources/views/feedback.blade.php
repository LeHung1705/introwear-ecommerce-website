@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/feedback.css') }}" />
@endpush
@section('content')

<div class="feedback-container">
    <div class="feedback-box">
        <h2>FEEDBACK</h2>

        @if(session('success'))
            <div class="success-msg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="color:red">
        {{ session('error') }}
            </div>
        @endif
      
        <form method="POST" action="{{ route('feedback.secure.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" value="{{ Auth::check() ? Auth::user()->name : 'Guest' }}" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" placeholder="Nhập email của bạn">
            </div>

            <div class="form-group">
                <label>Nội dung</label>
                <textarea name="message" placeholder="Nhập nội dung feedback"></textarea>
            </div>

            <div class="form-group">
                <label>Upload ảnh (tuỳ chọn)</label>
                <input type="file" name="image">
            </div>

            <button type="submit" class="submit-btn">Gửi Feedback</button>
        </form>
    </div>
</div>

@endsection