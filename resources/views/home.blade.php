@extends('layouts.app')

@section('title', 'BBW Heroes - Where BBW IT students come together')

@section('content')
<section class="px-6 py-16 md:px-12 md:py-32">
    <h1 class="mb-12 text-center text-4xl font-medium text-white md:text-7xl max-w-7xl m-auto">
        Where BBW IT students come together to form a community.
    </h1>
    <div class="flex justify-center gap-4">
        <a href="{{ route('contribute') }}" class="rounded-sm bg-bbw-400 px-3 py-1.5 text-gray-900 duration-100 hover:bg-bbw-500">
            Contribute
        </a>
    </div>
</section>

<hr class="border-gray-800">

<section class="px-6 py-16 md:px-12">
    <h2 class="mb-12 text-center text-2xl text-white md:text-4xl">
        Search from <strong>{{ $projectsCount }}</strong> projects
    </h2>
    
    @include('partials.projects-search', ['projects' => $projects])
</section>
@endsection
