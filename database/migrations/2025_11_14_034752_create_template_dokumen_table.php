<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_dokumen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_template', 100);
            $table->enum('jenis_template', ['presensi', 'notulen']);
            $table->string('file_path', 500);
            $table->string('file_name');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
            
            // Indexes
            $table->index('jenis_template');
            $table->index('is_active');
        });

        // Insert default templates
        $this->insertDefaultTemplates();
    }

    /**
     * Insert default template data
     *
     * @return void
     */
    private function insertDefaultTemplates()
    {
        DB::table('template_dokumen')->insert([
            [
                'nama_template' => 'Template Presensi Rapat',
                'jenis_template' => 'presensi',
                'file_path' => 'templates/presensi_template.docx',
                'file_name' => 'presensi_template.docx',
                'deskripsi' => 'Template daftar hadir peserta rapat',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_template' => 'Template Notulen Rapat',
                'jenis_template' => 'notulen',
                'file_path' => 'templates/notulen_template.docx',
                'file_name' => 'notulen_template.docx',
                'deskripsi' => 'Template notulen hasil rapat',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('template_dokumen', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        
        Schema::drop('template_dokumen');
    }
}