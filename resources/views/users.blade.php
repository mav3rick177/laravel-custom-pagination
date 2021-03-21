<!doctype html>
<html>
<head>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
   
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Users List</title>
</head>
<body>
  <div class="container">
    {{ request()->input('direction') }}
    {{ print_r($cache) }}
    <form action="{{ route('users.list') }}" method="POST">
      @csrf
      
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1"> 1
        </label>
      </div>
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2"> 2
        </label>
      </div>
      <div class="form-check form-check-inline disabled">
        <label class="form-check-label">
          <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3" disabled> 3
        </label>
      </div>
      <div class="form-group row">
        <label for="sort" class="col-2 col-form-label">Sort</label>
        <div class="col-4">
          <select class="form-control" id="sort" name="sort">
            <option value=">" {{ ( isset($cache['sort']) && $cache['sort'] == '>') ? 'selected' : '' }}>asc</option>
            <option value="<" {{ ( isset($cache['sort']) && $cache['sort'] == '<') ? 'selected' : '' }}>desc</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="cursor" class="col-2 col-form-label">Cursor</label>
        <div class="col-4">
          <select class="form-control" id="cursor" name="cursor">
            <option value="id" {{ ( isset($cache['cursor']) && $cache['cursor'] == 'id') ? 'selected' : '' }}>ID</option>
            <option value="dob"{{ ( isset($cache['cursor']) && $cache['cursor'] == 'dob') ? 'selected' : '' }}>Date of birth</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="perPage" class="col-2 col-form-label">Elements per page</label>
        <div class="col-4">
          <input class="form-control" type="number" value="{{ isset($cache['perPage']) ? $cache['perPage'] : '' }}" id="perPage" name="perPage">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-date-input" class="col-2 col-form-label">From</label>
        <div class="col-10">
          <input class="form-control" type="date" value="{{ isset($cache['from']) ? $cache['from'] : '' }}" id="example-date-input" name="from">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-date-input" class="col-2 col-form-label">To</label>
        <div class="col-10">
          <input class="form-control" type="date" value="{{ isset($cache['to']) ? $cache['to'] : '' }}" id="example-date-input" name="to">
        </div>
      </div>
      <div class="form-group">
        <label for="exampleFormControlSelect2">Example multiple select</label>
        <select multiple class="form-control" id="exampleFormControlSelect2">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12 text-center">
          <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Date Of Birth</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items->records as $user)
            <tr>
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->dob }}</td>
                <td><a href="{{ route('users.edit', [$user->id]) }}" class="btn btn-secondary">Edit</a></td>
            </tr>
        @endforeach
      </tbody>
    </table>
    @include('_custom-pagination', ['paginator' => $items])
    {{ $items->render() }}
    {{ $items->toJson() }}
  </div>
</body>
</html>