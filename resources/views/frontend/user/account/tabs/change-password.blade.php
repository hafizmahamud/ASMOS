{{ html()->form('PATCH', route('frontend.auth.password.update'))->class('form-horizontal')->open() }}
    <div class="row">
        <div class="col">
            {{ html()->label(__('validation.attributes.frontend.old_password'))->for('old_password') }}
            <div class="input-container">
                <input class="input-field" type="password" name="old_password" id="old_password" placeholder="@lang('validation.attributes.frontend.old_password')" autofocus required>
                <i class="fa fa-fw fa-eye field_icon old_password iconic"></i>
            </div>
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            {{ html()->label(__('validation.attributes.frontend.password'))->for('password') }}
            <div class="input-container">
                <input class="input-field" type="password" name="password" id="pass" placeholder="@lang('validation.attributes.frontend.password')" required>
                <i class="fa fa-fw fa-eye field_icon pass iconic"></i>
            </div>
                <p style="font-size:12px; color:red">@lang('labels.frontend.user.profile.pass_desc')</p>
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->for('password_confirmation') }}
            <div class="input-container">
                <input class="input-field" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('validation.attributes.frontend.password_confirmation')" required>
                <i class="fa fa-fw fa-eye field_icon password_confirmation iconic"></i>
            </div>
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group mb-0 clearfix">
                {{ form_submit(__('labels.general.buttons.update') . ' ' . __('validation.attributes.frontend.password')) }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->
{{ html()->form()->close() }}
