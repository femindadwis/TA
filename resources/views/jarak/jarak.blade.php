@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->
        <div class="container-fluid">
<div class="page-header">
<div class="row">


 <!-- Feature Unable /Disable Order Starts-->
 <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5>Tabel Jarak           </h5>
            <span>Disabling features that you don't wish to use for a particular table is easily done by setting a variable in the initialisation object</span>
            <span>In the following example only the search feature is left enabled (which it is by default).</span><br>
        {{-- <form class="form theme-form" action="{{ route('jarak.create') }}" method="post"> --}}
            {{-- @csrf --}}

                {{-- <button class="btn btn-primary text-white btn-sm btn-outline-dark" type="submit">
                    submit
                </button> --}}
        {{-- </form></div> --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-2">
                    <thead>
                        <tr>
                            <th>Lokasi</th>
                            @foreach ($locations as $location)
                                <th>{{ $location->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr>
                                <td>{{ $location->name }}</td>
                                @foreach ($locations as $destLocation)
                                    <td>
                                        @if ($location->id === $destLocation->id)
                                            -
                                        @elseif (isset($distances[$location->id][$destLocation->id]))
                                            {{ $distances[$location->id][$destLocation->id] }} km
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>





                        </div></div></div></div></div></div></div>
                        @include('layout.footer')
                        @include('layout.js')


{{--
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Distance Matrix</h1>

    <table class="table">
        <thead>
            <tr>
                <th>From / To</th>
                @foreach ($locations as $location)
                <th>{{ $location->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $key => $location)
            <tr>
                <th>{{ $location->name }}</th>
                @foreach ($distances[$key] as $distance)
                <td>{{ $distance }}</td>
                @endforeach
                {{-- <td>{{ $distances[$key][$location->id] }}</td> --}}

