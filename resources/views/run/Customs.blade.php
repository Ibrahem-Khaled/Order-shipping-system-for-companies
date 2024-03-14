@extends('layouts.default')

@section('content')

    <form action="{{ route('getOfices') }}" class="row align-items-center" method="GET">
        <div class="col">
            <input type="text" name="query" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    </form>

    <div class="container mt-5">
        <table class="table">
            <a class="nav-link" href="{{ route('addOffice', 'client') }}">
                <i class="fas fa-plus"></i> اضافة مكتب
            </a>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">عدد الحاويات</th>
                    <th scope="col">عدد البيان</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>
                        <td><a href="{{ route('getOfficeContainerData', $item->id) }}">{{ $item->name }}</a></td>
                        <td>{{ $item->container->whereIn('status', ['wait', 'rent'])->count() }}</td>
                        <td>
                            @php
                                $containerCount = 0;
                                foreach ($item->customs as $custom) {
                                    $customing = \App\Models\CustomsDeclaration::find($custom->id);
                                    if ($customing->container->whereIn('status', ['wait', 'rent'])->count() > 0) {
                                        $containerCount++;
                                    }
                                }
                                echo $containerCount;
                            @endphp
                        </td>
                        <td>
                            <!-- Button to trigger the modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#myModal{{ $item->id }}">
                                اضافة بيان جمركي
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="myModal{{ $item->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">اضافة بيان جمركي</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Your form content goes here -->
                                            <form action="{{ route('postCustoms', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">رقم
                                                        البيان</label>
                                                    <input type="number" class="form-control" required
                                                        name="statement_number" id="exampleFormControlInput1"
                                                        placeholder="123">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">اسم
                                                        العميل</label>
                                                    <input type="text" class="form-control" required name="subClient"
                                                        id="exampleFormControlInput1" placeholder="العميل">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">عدد
                                                        الحاويات</label>
                                                    <input type="number" class="form-control" required name="contNum"
                                                        id="exampleFormControlInput1" placeholder="1">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">انهاء</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        {{-- data-bs-toggle="modal"
                                                    data-bs-target="#myModal2" --}}>التالي</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
@stop
