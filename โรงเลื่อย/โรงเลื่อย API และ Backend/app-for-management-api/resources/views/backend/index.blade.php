@extends('backend._template._master')

@section('content')
<div class="row marketing main-menu">
	@include('backend._template.breadcrumb')

	@if (\Auth::user()->type == 'Admin')
		<div class="col-lg-6">
			<a href="{{ url('backend/wood-piece') }}">
				<div class="panel panel-primary">
					<div class="panel-body bg-gray text-center bg-logs">
						<div class="fs-19">เพิ่มข้อมูลไม้ท่อน</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-6">
			<a href="{{ url('backend/fire-wood') }}">
				<div class="panel panel-primary">
					<div class="panel-body bg-gray text-center bg-firewood">
						<div class="fs-19">เพิ่มข้อมูลไม้ฟืน</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-6">
			<a href="{{ url('backend/profit-loss') }}">
				<div class="panel panel-primary">
					<div class="panel-body bg-gray text-center bg-profit">
						<div class="fs-19">เพิ่มข้อมูลประมาณการกำไรขาดทุน</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-6">
			<a href="{{ url('backend/performance') }}">
				<div class="panel panel-primary">
					<div class="panel-body bg-gray text-center bg-performance">
						<div class="fs-19">เพิ่มข้อมูล Performance Intensive</div>
					</div>
				</div>
			</a>
		</div>
	@else
		<div class="col-lg-6">
			<a href="{{ url('backend/user') }}">
				<div class="panel panel-primary">
					<div class="panel-body bg-gray text-center bg-user">
						<div class="fs-19">ข้อมูลผู้ใช้งานระบบ</div>
					</div>
				</div>
			</a>
		</div>
	@endif
</div>
@endsection
