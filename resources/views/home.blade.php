@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('no_gender') == 'no_gender')
                        <div>
                            <div class="alert alert-warning">
                                <span>Uh Oh! We couldn't determine your gender 😢</span>
                            </div>
                            <form method="post" action="{{route('users.update')}}">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}

                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="gender">Select Your Gender</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="gender" class="form-control form-control-lg">
                                            <option>Please Select a Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary mb-2">Tell Us</button>
                                    </div>
                                </div>
                            </form>

                        </div>

                    @else
                        Ok, you are <strong>{{ Auth::user()->gender}}</strong> !
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
