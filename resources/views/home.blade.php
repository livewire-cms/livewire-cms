@extends('layouts.app')

@section('title')
    {{ __('Dashboard') }}
@endsection

@section('content')

    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Dashboard') }}
    </h2>

    <div class="flex justify-center items-center">


        <div class="p-2">
            <a
            href="/backend/test"
            class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
            > 已加载的Module</a>
        </div>

    </div>

@endsection
