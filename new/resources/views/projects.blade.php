@extends('layouts.app')

@section('title', 'Projects - BBW Heroes')

@section('content')
<section class="px-6 py-16 md:px-12">
    <h1 class="mb-12 text-center text-3xl font-medium text-white md:text-5xl">
        Browse <strong>{{ $projectsCount }}</strong> Projects
    </h1>
    
    <p class="mb-8 text-center text-sm text-gray-400">
        <em>GitHub repositories are cached for 1 hour</em>
    </p>
    
    @include('partials.projects-search', ['projects' => $projects, 'limit' => 50])
</section>
@endsection
