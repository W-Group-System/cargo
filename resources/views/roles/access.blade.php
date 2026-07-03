<div class="table-response">
    <form id="updateAccessForm" method="POST">

        @csrf
        <input type="hidden" name="id" value="{{ $roleId }}">
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th class="bg-primary text-white">Module</th>
                    <th class="bg-primary text-white text-center">Create</th>
                    <th class="bg-primary text-white text-center">Read</th>
                    <th class="bg-primary text-white text-center">Update</th>
                    <th class="bg-primary text-white text-center">Delete</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($access as $key => $value)
                    <tr>
                        <td colspan="5">
                            <b>{{ $key }}</b>
                        </td>
                    </tr>
                    @foreach ($value as $k => $v)
                        <tr>
                            <td class="text-center">
                                {{ $k }}
                            </td>
                            <td class="text-center">
                                <input type="checkbox"
                                    name="permission[{{ $v['module_id'] }}][create]"
                                    value="1"
                                    {{ $v['canCreate'] == "1" ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="checkbox"
                                    name="permission[{{ $v['module_id'] }}][read]"
                                    value="1"
                                    {{ $v['canRead'] == "1" ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="checkbox"
                                    name="permission[{{ $v['module_id'] }}][update]"
                                    value="1"
                                    {{ $v['canUpdate'] == "1" ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="checkbox"
                                    name="permission[{{ $v['module_id'] }}][delete]"
                                    value="1"
                                    {{ $v['canDelete'] == "1" ? 'checked' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        @if ($canUpdate)
            <button class="btn btn-primary form-control" type="submit">
                Save
            </button>
        @endif
    </form>
</div>