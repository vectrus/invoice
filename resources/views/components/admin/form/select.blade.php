<div class="form-group mb-3">
    <select id="country-dropdown" class="form-control">
        <option value="">-- Select Country --</option>
        @foreach ($clients as $data)
            <option value="{{$data->id}}">
                {{$data->name}}
            </option>
        @endforeach
    </select>
</div>
