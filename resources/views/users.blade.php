<!doctype html>
<html>
<head>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
   
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div class="container">
    {{ request()->input('direction') }}
    <form>
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
          <select class="form-control" id="sort">
            <option>asc</option>
            <option>desc</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="perPage" class="col-2 col-form-label">Elements per page</label>
        <div class="col-4">
          <input class="form-control" type="number" value="10" id="perPage">
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
    </form>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Date Of Birth</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $user)
            <tr>
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->dob }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>
    @include('_custom-pagination', ['paginator' => $items])
    {{ $items->toJson() }}
  </div>
</body>
</html>