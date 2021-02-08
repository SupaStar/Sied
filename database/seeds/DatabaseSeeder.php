<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(EntidadFederativa::class);
        $this->call(Nacionalidades::class);
        $this->call(Paises::class);
        $this->call(DestinoRecursos::class);
        $this->call(OrigenRecursos::class);
        $this->call(Divisas::class);
        $this->call(InstrumentoMonetario::class);
        $this->call(Profesion::class);
        $this->call(ActividadGiro::class);
        $this->call(Antiguedad::class);
        $this->call(Edad::class);
        $this->call(PersonalidadJuridica::class);
        $this->call(FederativaResidencia::class);
        $this->call(NacionalidadAntecedentes::class);
        $this->call(Pld::class);
        $this->call(Factores::class);
        $this->call(Riesgo::class);
        $this->call(Ponderacion::class);
        $this->call(PepExtranjeras::class);
        $this->call(PepMexicanas::class);

    }
}
