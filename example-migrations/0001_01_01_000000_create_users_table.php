$table->bigIncrements('id'); 	Incrementing ID using a "big integer" equivalent.
$table->bigInteger('votes'); 	BIGINT equivalent to the table
$table->binary('data'); 	BLOB equivalent to the table
$table->boolean('confirmed'); 	BOOLEAN equivalent to the table
$table->char('name', 4); 	CHAR equivalent with a length
$table->date('created_at'); 	DATE equivalent to the table
$table->dateTime('created_at'); 	DATETIME equivalent to the table
$table->decimal('amount', 5, 2); 	DECIMAL equivalent with a precision and scale
$table->double('column', 15, 8); 	DOUBLE equivalent with precision, 15 digits in total and 8 after the decimal point
$table->enum('choices', array('foo', 'bar')); 	ENUM equivalent to the table
$table->float('amount'); 	FLOAT equivalent to the table
$table->increments('id'); 	Incrementing ID to the table (primary key).
$table->integer('votes'); 	INTEGER equivalent to the table
$table->longText('description'); 	LONGTEXT equivalent to the table
$table->mediumInteger('numbers'); 	MEDIUMINT equivalent to the table
$table->mediumText('description'); 	MEDIUMTEXT equivalent to the table
$table->morphs('taggable'); 	Adds INTEGER taggable_id and STRING taggable_type
$table->nullableTimestamps(); 	Same as timestamps(), except allows NULLs
$table->smallInteger('votes'); 	SMALLINT equivalent to the table
$table->tinyInteger('numbers'); 	TINYINT equivalent to the table
$table->softDeletes(); 	Adds deleted_at column for soft deletes
$table->string('email'); 	VARCHAR equivalent column
$table->string('name', 100); 	VARCHAR equivalent with a length
$table->text('description'); 	TEXT equivalent to the table
$table->time('sunrise'); 	TIME equivalent to the table
$table->timestamp('added_on'); 	TIMESTAMP equivalent to the table
$table->timestamps(); 	Adds created_at and updated_at columns
$table->rememberToken(); 	Adds remember_token as VARCHAR(100) NULL
->nullable() 	Designate that the column allows NULL values
->default($value) 	Declare a default value for a column
->unsigned() 	Set INTEGER to UNSIGNED