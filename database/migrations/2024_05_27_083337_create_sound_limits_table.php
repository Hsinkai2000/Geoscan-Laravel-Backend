<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('sound_limits');
        Schema::create('sound_limits', function (Blueprint $table) {
            $table->id();
            $table->string('category', 255)->default('Residential buildings');
            $table->integer('measurement_point_id')->nullable()->index();

            $table->float('mon_sat_7am_7pm_leq5min')->default(90)->nullable();
            ;
            $table->float('mon_sat_7pm_10pm_leq5min')->default(70)->nullable();
            ;
            $table->float('mon_sat_10pm_7am_leq5min')->default(55)->nullable();
            ;

            $table->float('sun_ph_7am_7pm_leq5min')->default(75)->nullable();
            ;
            $table->float('sun_ph_7pm_10pm_leq5min')->default(55)->nullable();
            ;
            $table->float('sun_ph_10pm_7am_leq5min')->default(55)->nullable();
            ;

            $table->float('mon_sat_7am_7pm_leq12hr')->default(75)->nullable();
            ;
            $table->float('mon_sat_7pm_10pm_leq12hr')->default(65)->nullable();
            ;
            $table->float('mon_sat_10pm_7am_leq12hr')->default(55)->nullable();
            ;

            $table->float('sun_ph_7am_7pm_leq12hr')->default(75)->nullable();
            ;
            $table->float('sun_ph_7pm_10pm_leq12hr')->default(140)->nullable();
            ;
            $table->float('sun_ph_10pm_7am_leq12hr')->default(140)->nullable();
            ;

            // $table->float('mon_sat_10pm_12am_leq5min')->default(55)->nullable();
            // ;
            // $table->float('mon_sat_12am_7am_leq5min')->default(55)->nullable();
            // ;

            // $table->float('sun_ph_10pm_12am_leq5min')->default(55)->nullable();
            // ;
            // $table->float('sun_ph_12am_7am_leq5min')->default(55)->nullable();
            // ;

            // $table->float('mon_sat_10pm_12am_leq12hr')->default(55)->nullable();
            // ;
            // $table->float('mon_sat_12am_7am_leq12hr')->default(55)->nullable();
            // ;

            // $table->float('sun_ph_10pm_12am_leq12hr')->default(140)->nullable();
            // ;
            // $table->float('sun_ph_12am_7am_leq12hr')->default(140)->nullable();
            // ;

            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sound_limits');
    }
};