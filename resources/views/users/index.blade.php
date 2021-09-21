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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">Role</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($items) > 0)
                            @foreach ($items as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->roles()->first()->name }}</td>
                                <td class="d-flex">
                                    @permission('view.users')
                                    <button class="mx-2">
                                        <a href="{{ route('users.show', $item->id) }}"><i class="far fa-eye"></i></a>
                                    </button>
                                    @endpermission

                                    @permission('edit.users')
                                    <button class="mx-2">
                                        <a href="{{ route('users.edit', $item->id) }}"><i class="fas fa-edit"></i></a>
                                    </button>
                                    @endpermission
                                    @permission('delete.users')
                                    <form action="{{ route('users.destroy', $item->id) }}" method="POST" class="mx-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"><i class="far fa-trash-alt"></i></button>
                                    </form>
                                    @endpermission
                                </td>
                              </tr>
                            @endforeach
                            
                            @endif
                          </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>