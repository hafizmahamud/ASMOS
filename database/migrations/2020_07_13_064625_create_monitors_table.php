<?php

use Spatie\UptimeMonitor\Models\Enums\CertificateStatus;
use Spatie\UptimeMonitor\Models\Enums\UptimeStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->unique();
            $table->string('ip')->nullable();
            $table->string('label')->nullable();
            $table->string('active')->nullable();
            $table->integer('timeout')->nullable();
            $table->string('pattern')->nullable();
            $table->string('status')->nullable();
            $table->string('status_log')->nullable();
            $table->boolean('uptime_check_enabled')->default(true);
            $table->string('uptime_before_status')->default('');
            $table->string('uptime_check_interval_in_minutes')->default(1);
            $table->string('uptime_status')->default(UptimeStatus::NOT_YET_CHECKED);
            $table->text('uptime_check_failure_reason')->nullable();
            $table->integer('uptime_check_times_failed_in_a_row')->default(0);
            $table->timestamp('uptime_status_last_change_date')->nullable();
            $table->timestamp('uptime_last_check_date')->nullable();
            $table->timestamp('uptime_check_failed_event_fired_on_date')->nullable();
            $table->string('uptime_check_method')->default('get');
            $table->text('uptime_check_payload')->nullable();
            $table->text('uptime_check_additional_headers')->nullable();
            $table->string('uptime_check_response_checker')->nullable();
            $table->string('web_server')->nullable();
            $table->boolean('certificate_check_enabled')->default(true);
            $table->string('certificate_status')->default(CertificateStatus::NOT_YET_CHECKED);
            $table->string('certificate_before_status')->nullable();
            $table->timestamp('certificate_expiration_date')->nullable();
            $table->string('certificate_issuer')->nullable();
            $table->string('certificate_check_failure_reason')->default('');
            $table->string('metric')->nullable();
            $table->string('host_utilization')->nullable();
            $table->string('disk')->nullable();
            $table->string('database')->nullable();
            $table->string('database_name')->nullable();
            $table->integer('database_port')->nullable();
            $table->string('database_username')->nullable();
            $table->string('database_password')->nullable();
            $table->string('database_status')->nullable();
            $table->string('database_output')->nullable();
            $table->string('api')->nullable();
            $table->string('api_code')->nullable();
            $table->string('api_output')->nullable();
            $table->string('email')->nullable();
            $table->string('sms')->nullable();
            $table->integer('issue')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('monitors');
    }
}
