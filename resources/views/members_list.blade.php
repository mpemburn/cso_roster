@extends('layouts.app')
@section('content')
    <div class="content">
        <table id="main_member_list" class="member-list">
            <thead>
            <tr>
                <td>Name</td>
                <td class="show-sm-up">Address</td>
                <td>City</td>
                <td>State</td>
                <td class="show-sm-up">Zip</td>
                <td>Home Phone</td>
                <td>Cell Phone</td>
                <td class="show-sm-up">Email</td>
                <td class="show-lg-up">Board Role</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($members as $member)
                <tr data-id="{{ $member->id }}">
                    <td class="nobr">{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td class="show-sm-up">{{ $member->address_1 }}</td>
                    <td>{{ $member->city }}</td>
                    <td>{{ $member->state }}</td>
                    <td class="show-sm-up">{{ $member->zip }}</td>
                    <td class="nobr">{{ $member->home_phone }}</td>
                    <td class="nobr">{{ $member->cell_phone }}</td>
                    <td class="show-lg-up">{{ $member->email }}</td>
                    <td class="show-lg-up"></td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
    @endsection
            <!-- Push any scripts needed for this page onto the stack -->
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/js/lib') }}/typeahead.bundle.min.js"></script>
    @endpush