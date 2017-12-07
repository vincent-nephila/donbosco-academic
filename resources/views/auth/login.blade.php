@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		 <div class="col-md-6">
                     
                     
                     <h1>Vision - Mission </h1>
                     
                     <h4>
                            Forming GOOD CHRISTIANS and UPRIGHT CITIZENS.</h4>
                     
                     <p>
                         
                         We, the Educative Pastoral Community of <strong> Don Bosco Technical Institute of Makati</strong>, envision a learning community, committed to educate and evangelize the young to become upright citizens and good Christians.
We provide quality education and holistic formation towards social transformation through a curriculum with technological orientation that promotes Gospel values.
Inspired by Mary Help of Christians and guided by the Preventive System of St. John Bosco, we animate the young to "choose the better things" and to live the Salesian Youth Spirituality.
                     </p>
               
               
               </div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">Login</div>
				<div class="panel-body">
                                   
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					@if (session('message'))
						<div class="alert alert-danger">
							<strong>Sorry!</strong> You cannot access this with your current access level.
						</div>
                                        <?php session()->flush(); ?>
					@endif
                                    
                                        
					<form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
						  {!! csrf_field() !!}

						<div class="form-group">
							<label class="col-md-4 control-label">DBTS ID Number</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="idno" value="">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Login</button>

								<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
