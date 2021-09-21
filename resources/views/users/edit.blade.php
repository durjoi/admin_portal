<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>

        @permission('create.users')
        <button><a href="{{ route('users.create') }}">Add New</a></button>
        @endpermission
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('users.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $item->name }}" id="name">
                            @error('name')
                                <div class="alert alert-danger my-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" value="{{ $item->email }}" name="email">
                            @error('email')
                                <div class="alert alert-danger my-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <div class="alert alert-danger my-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select mb-3" aria-label="Default select example" id="role" name="role">
                                <option></option>
                                @if(count($roles)>0) 
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" @if($item->roles()->first()->name === $role->name) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                @endif
                              </select>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Update">

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>