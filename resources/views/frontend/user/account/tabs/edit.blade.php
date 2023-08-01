{{ html()->modelForm($logged_in_user, 'POST', route('frontend.user.profile.update'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
    @method('PATCH')

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('validation.attributes.frontend.first_name'))->for('first_name') }}

                {{ html()->text('first_name')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.first_name'))
                    ->attribute('maxlength', 191)
                    ->required()
                    ->autofocus() }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('validation.attributes.frontend.last_name'))->for('last_name') }}

                {{ html()->text('last_name')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.last_name'))
                    ->attribute('maxlength', 191)
                    ->required() }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('validation.attributes.frontend.username'))->for('username') }}

                {{ html()->text('username')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.username'))
                    ->attribute('maxlength', 191)
                    ->required() }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}

                {{ html()->email('email')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.email'))
                    ->attribute('maxlength', 191)
                    ->required() }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('validation.attributes.frontend.mobile'))->for('mobile') }}

                {{ html()->number('mobile')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.mobile'))
                    ->attribute('maxlength', 191)
                    ->required() }}
                <p style="font-size:12px; color:red">@lang('labels.frontend.user.profile.mobile_desc')</p>
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label>@lang('validation.attributes.frontend.role')</label>
                @if($logged_in_user->role =='admin')
                <select name="role" class="form-control" style="width: 100%;" required>
                <option selected="selected" value="admin">@lang('validation.attributes.frontend.admin')</option>
                <option value="user">@lang('validation.attributes.frontend.user')</option>
                </select>
                @elseif($logged_in_user->role =='user')
                <input type="text" class="form-control" name="user" value="{{$logged_in_user->role}}" disabled>
                <input type="hidden" class="form-control" name="role" value="{{$logged_in_user->role}}">
                @else
                <select name="role" class="form-control" style="width: 100%;" required>
                <option value="">@lang('validation.attributes.frontend.please')</option>
                <option value="admin">@lang('validation.attributes.frontend.admin')</option>
                <option value="user">@lang('validation.attributes.frontend.user')</option>
                </select>
                @endif
            </div>
        </div>
    </div>

    <div class="row" style="margin-bottom: 3%;">
        <div class="col">
            <div class="form-group mb-0 clearfix">
                {{ form_submit(__('labels.general.buttons.update')) }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->
{{ html()->closeModelForm() }}

@push('after-scripts')
@endpush
