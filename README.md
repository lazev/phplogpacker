# phplogpacker

Compacta e arquiva arquivos depois que chegam a um tamanho definido.


***Forma de usar***

```php logpacker.php /pasta/destino/```

Todos arquivos dentro da /pasta/destino e também subpastas que estiverem com mais de 50MB serão compactados


***7Zip***

Por padrão o script usa compactação pelo 7Zip, se este compactador estiver instalado na máquina, caso contrário, usa o Zip.


***Personalização***

O script procura na pasta raíz pelo arquivo logpacker.ini. Através dele é possível alterar as configurações de arquivamento.

O arquivo tem que estar no seguinte formato:

```
num_files_archived = 5  ; número de arquivos zipados guardados antes da exclusão
max_file_size_MB   = 50 ; tamanho limite do arquivo antes dele ser capturado pelo arquivamento
days_last_change   = 0  ; A FAZER - arquivos que foram modificados a este número de dias, entram no arquivamento
days_from_creation = 0  ; A FAZER - arquivos que foram criados a este número de dias, entram no arquivamento
archive_extension  = 7z ; extensão padrão do arquivo compactado, ela define o uso de 7Zip ou Zip.
```
