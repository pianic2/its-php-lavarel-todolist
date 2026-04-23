<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'Laravel TODO') }} - Login</title>
	<link rel="stylesheet" href="{{ asset('css/core.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/project.css') }}">
</head>
<body>
	<main style="display:flex;align-items:center;justify-content:center;min-height:100vh;padding:2rem;">
		<div style="width:380px;">
			<h2 style="margin-bottom:1rem;">Accedi</h2>

			@if(session('warning'))
				<div class="alert alert--warning">{{ session('warning') }}</div>
			@endif

			@if(session('error'))
				<div class="alert alert--danger">{{ session('error') }}</div>
			@endif

			@if($errors->any())
				<div class="alert alert--danger">
					<ul style="margin:0;padding-left:1.25rem;">
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form method="POST" action="{{ route('login') }}">
				@csrf

				<div style="margin-bottom:0.75rem;">
					<label for="email">Email</label>
					<input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus style="width:100%;padding:0.5rem;margin-top:0.25rem;">
					@error('email')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div style="margin-bottom:0.75rem;">
					<label for="password">Password</label>
					<input id="password" type="password" name="password" required style="width:100%;padding:0.5rem;margin-top:0.25rem;">
					@error('password')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div style="margin-bottom:0.75rem;">
					<label><input type="checkbox" name="remember"> Ricordami</label>
				</div>

				<div>
					<button type="submit" class="button button--primary" style="width:100%;padding:0.5rem;">Accedi</button>
				</div>
			</form>
		</div>
	</main>

	<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
