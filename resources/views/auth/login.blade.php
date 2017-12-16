<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ isset($errors['email']) ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email"
                                       type="text"
                                       class="form-control"
                                       name="email"
                                       value="{{ $data['email'] ?? '' }}"
                                       required autofocus>

                                @if (isset($errors['email']))
                                    <span class="help-block">
                                        <strong>{{ $errors['email'] }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ isset($errors['password']) ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if (isset($errors['password']))
                                    <span class="help-block">
                                        <strong>{{ $errors['password'] }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                {{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
                                    {{--Forgot Your Password?--}}
                                {{--</a>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
