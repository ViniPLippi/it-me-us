
## Erro de chave duplicada

Como o banco de dados é copiado entre os ambientes por meio de *DUMP*, eventualmente as chaves primárias de algumas tabelas podem ficar dessincronizadas das sequências de auto-geração.

Por exemplo, a tabela **alunos** é definida assim:

```sql
CREATE TABLE
  public.alunos (
    id bigserial NOT NULL DEFAULT nextval('alunos_id_seq'::regclass),
    alu_nome character varying(50) NOT NULL,
    alu_rge character varying(15) NOT NULL,
    alu_nasc date NOT NULL,
    alu_est_id integer NOT NULL,
    created_at timestamp(0) without time zone NULL,
    updated_at timestamp(0) without time zone NULL
  );

ALTER TABLE
  public.alunos
ADD
  CONSTRAINT alunos_pkey PRIMARY KEY (id)
```

O atributo **id** é registrado com a função `nextval('alunos_id_seq'::regclass)` para o valor padrão. A sequência identificada então por **alunos_id_seq** é definida assim:

```sql
CREATE SEQUENCE alunos_id_seq;
```

Caso a sequência esteja fora de sincronia com o conteúdo da tabela, o seguinte erro pode ocorrer:

```log
[2023-05-23 13:37:39] local.ERROR: SQLSTATE[23505]: Unique violation: 7 ERROR:  duplicate key value violates unique constraint "alunos_pkey"
DETAIL:  Key (id)=(7) already exists. (SQL: insert into "alunos" ("alu_nome", "alu_rge", "alu_nasc", "alu_est_id", "updated_at", "created_at") values (Aluno de Teste, 31415926535, 1901-01-01, 1, 2023-05-23 13:37:39, 2023-05-23 13:37:39) returning "id") {"userId":2,"exception":"[object] (Illuminate\\Database\\QueryException(code: 23505): SQLSTATE[23505]: Unique violation: 7 ERROR:  duplicate key value violates unique constraint \"alunos_pkey\"
DETAIL:  Key (id)=(7) already exists. (SQL: insert into \"alunos\" (\"alu_nome\", \"alu_rge\", \"alu_nasc\", \"alu_est_id\", \"updated_at\", \"created_at\") values (Aluno de Teste, 31415926535, 1901-01-01, 1, 2023-05-23 13:37:39, 2023-05-23 13:37:39) returning \"id\") at /dist/flualfa/vendor/laravel/framework/src/Illuminate/Database/Connection.php:712)
```

Para corrigir usamos a seguinte cadeia de comandos:

Obter o valor máximo na tabela de **alunos**:

```sql
SELECT MAX(id) FROM alunos;
```

Obter a próxima sequência - deve ser maior que o valor obtido anteriormente:

```sql
SELECT nextval('alunos_id_seq');
```

Se não for maior, então executar os comandos abaixo para corrigir, protegendo a tabela contra inserções enquanto atualizamos a sequência:

```sql
BEGIN;

    LOCK TABLE alunos IN EXCLUSIVE MODE;

    SELECT setval('alunos_id_seq', COALESCE((SELECT MAX(id)+1 FROM alunos), 1), false);

COMMIT;
```
