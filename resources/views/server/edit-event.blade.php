@extends('master')

@section('content')

        <div class="container-fluid">

            <div class='row'>
                <div class="col-md-10 col-lg-8">
                    <div class='box z-depth-1'>
                        <h1 class="title">Edit Event: {{$event->sch_title}}</h1>
                    <form method="POST" action="/cm/event/update" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('conflict'))
                            <div class="alert alert-danger">
                          Could not create event. There was a time conflict with the event: <br>
                          <strong>{{ session('conflict')->sch_title }}</strong>
                          [{{ date('H:i', strtotime(session('conflict')->sch_start_time)) }} - {{ date('H:i', strtotime(session('conflict')->sch_end_time)) }}]
                        </div>
                        @endif
                        <input type="hidden" name="id" value="{{$event->sch_id}}">
                        <input style="display:none" name="clear" id="clrLogo" type="checkbox">

                        <div class="row">

                              <div class="col-md-6">
                        <div class="form-group">
                          <label for="room">Room</label>
                        <select class="form-control" name="room">
                            @foreach($rooms as $room)
                                <option value="{{$room->rom_id}}" @if($roomID == $room->rom_id) selected @endif>{{$room->rom_name}}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                        <div class="col-md-6">
                        <div class="form-group">
                          <label for="style">Style</label>
                        <select class="form-control" name="style">
                            @foreach(config('conference.styles') as $style)
                                <option value="{{$style}}" @if($event->sch_style == $style) selected @endif>{{ ucfirst($style) }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                    </div>

                    <div class="row">

                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="start">Start Date</label>
                              <div class='input-group date' class="datetime-picker" id='startDatePicker'>
                              <input type='text' class="form-control" name="start" value="{{ $event->sch_start_time }}"/>
                              <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                              </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="end">End Date</label>
                              <div class='input-group date' class="datetime-picker" id='endDatePicker'>
                              <input type='text' class="form-control" name="end" value="{{ $event->sch_end_time }}"/>
                              <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                              </div>
                          </div>
                      </div>

                  </div>

                      <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $event->sch_title }}">
                      </div>
                      <div class="form-group">
                        <label for="details">Details (Optional)</label>
                        <textarea class="form-control" name="detail" rows="5">{{ $event->sch_detail }}</textarea>
                      </div>

                    <div class="row">

                          <div class="col-md-6">

                              <div class="form-group">
                                <label for="logo">Logo (Optional)</label>
                                <input type="file" name="logo" id="logoSelect">
                                {{-- <p class="help-block">Example block-level help text here.</p> --}}
                              </div>

                              <div class="checkbox @if(is_null($event->sch_logo)) hide @endif" id="fullLogo">
                                <label>
                                  <input name="fullscreen" type="checkbox" @if($event->sch_logo_fullscreen) " checked " @endif> Fullscreen Logo
                                </label>
                              </div>

                                  <a href="/cm/events" class="btn btn-default">Cancel</a>
                              <a href="/cm/event/delete/{{$event->sch_id}}/{{$roomID}}" class="btn btn-danger">Delete</a>

                              <button type="submit" class="btn btn-primary">Update</button>
                              <button type="button" id="clearLogo" class="btn btn-warning @if(is_null($event->sch_logo)) hide @endif">Clear Logo</button>

                        </div>

                          <div class="col-md-6">
                              <div id="logoPreview">
                                   @if(! is_null($event->sch_logo))
                                      <img src="data:image/png;base64, {{ base64_encode($event->sch_logo) }}" />
                                   @endif
                              </div>
                          </div>

                    </div>


                    </form>
                </div>
            </div>
            </div>
        </div>
@endsection
