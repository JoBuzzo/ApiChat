<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

/**
 * Classe para padronizar a manipulação de imagens do Projeto
 */

class FileHandlerService
{
    /**
     * Responsável por fazer upload do arquivo em qualquer método de inserção
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @param  string  $filePath
     * @return string
     * 
     * @author João Lucas Buzzo Holzle <joaolucas.buzzo@gmail.com> 
     */
    public static function store(UploadedFile $photo, string $filePath): string
    {
        self::handleFilePath($filePath);

        $photo->storeAs('public/' . $filePath, $nameFile = self::nameFile($photo));

        return $nameFile;
    }

    /**
     * Responsável por apagar o antigo arquivo caso ele exista, e adiciona um novo arquivo em qualquer método de edição
     *
     * @param  \Illuminate\Http\UploadedFile  $newPhoto
     * @param  string  $filePath
     * @param  string|null  $photo
     * @return string
     * 
     * @author João Lucas Buzzo Holzle <joaolucas.buzzo@gmail.com> 
     */
    public static function update(UploadedFile $newPhoto, string $filePath, string $photo = null): string
    {
        self::delete($photo, $filePath);

        $newPhoto->storeAs('public/' . $filePath, $nameFile = self::nameFile($newPhoto));

        return $nameFile;
    }

    /**
     *  Responsável por apagar o arquivo em qualquer método de exclusão
     *
     * @param  string  $photo
     * @param  string  $filePath
     * @return void
     * 
     * @author João Lucas Buzzo Holzle <joaolucas.buzzo@gmail.com> 
     */
    public static function delete(string $photo, string $filePath): void
    {
        if ($photo) {
            self::handleFilePath($filePath);

            unlink(storage_path('app/public/' . $filePath . $photo));
        }
    }

    /**
     * Responsável por dar um nome Hash para o arquivo
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return string
     * 
     * @author João Lucas Buzzo Holzle <joaolucas.buzzo@gmail.com> 
     */
    protected static function nameFile(UploadedFile $photo): string
    {
        return Str::random(40) . "." . $photo->getClientOriginalExtension();
    }

    /**
     * Responsável por tratar o caminho do arquivo com '/' 
     *
     * @param  string  $filePath
     * @return void
     * 
     * @author João Lucas Buzzo Holzle <joaolucas.buzzo@gmail.com> 
     */
    protected static function handleFilePath(string &$filePath)
    {
        $firstCharacter = substr($filePath, 0, 1);

        if ($firstCharacter == '/' || $firstCharacter == '\\') {
            $filePath = substr($filePath, 1);
        }

        $lastCharacter = substr($filePath, -1);

        if ($lastCharacter != '/' || $lastCharacter != '\\') {
            $filePath .= '\\';
        }
    }
}
