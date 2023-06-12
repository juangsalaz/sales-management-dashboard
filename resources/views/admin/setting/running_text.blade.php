@extends('admin.layout._layout')

@section('title', 'Manage Running Text')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Konten Running Text</h4>
               
                {{ Form::open(['url' => '/admin/settings/running-text/update','class'=>'border p-3']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <textarea class="form-control" id="konten" name="teks">{{ $running_text[0]->teks }}</textarea>
                        </div>
                    </div>
                    {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
                    <a href="{{route('dashboard')}}" class="btn btn-link" role="button">Batal</a>
                {{-- </form> --}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script src="{{asset('ckeditor/ckeditor.js')}}"></script>

<script>
    var konten = document.getElementById("konten");
    CKEDITOR.replace(konten,{
    language:'en-gb'
    });
    CKEDITOR.config.allowedContent = true;
</script>
@endsection
