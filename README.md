Yii2 ManyToMany Behavior
========================
This behavior makes it easy to maintain many-to-many and one-to-many relations in your ActiveRecord models.

*Note: Behavior is still under development and should be used with caution!*

Usage
-----
1. In your model, add the behavior and configure it
2. In your model, add validation rules for the attributes created by the behavior
3. In your view, create form fields for the attributes

Adding and configuring the behavior
-----------------------------------

As an example, let's assume you are dealing with entities like `Book`, `Author` and `Review`. The `Book` model has the following relationships:
```php
public function getAuthors()
{
    return $this->hasMany(Author::className(), ['id' => 'author_id'])
                ->viaTable('book_has_author', ['book_id' => 'id']);
}

public function getReviews()
{
    return $this->hasMany(Review::className(), ['id' => 'review_id']);
}
```
In the same model, the behaviour can be configured like so:
```php
public function behaviors()
{
    return [
        [
            'class' => \voskobovich\behaviors\ManyToManyBehavior::className(),
            'relations' => [
                'author_list' => 'authors',
				'review_list' => 'reviews',
            ],
        ],
    ];
}
```

### Custom getters and setters ###

Attributes lik `author_list` and `review_list` in the `Book` model are created automatically. By default, they are configured to accept data from a standard select input (see below). However, it is possible to use custom getter and setter functions, which may be useful for interaction with more complex frontend scripts:
```php
...
'author_list' => [
    'authors',
    'get' => function($value) {
        return JSON::encode($value);
    },
    'set' => function($value) {
        return JSON::decode($value);
    },
]
...
```
The setter function receives whatever data comes through the `$_REQUEST` and is expected to return the array of the related model IDs. The getter function receives the array of the related model IDs. 

### Setting default values for orphaned models ###

When one-to-many relations are saved, old links are removed and new links are created. To remove an old link, the corresponding foreign-key column is set to a certain value. It is `NULL` by default, but can be configured differently. Note that your database must support this, so if you are using `NULL` as a default value, the field must be nullable.

You can supply a constant value like so: 

```php
...
'review_list' => [
    'reviews',
    'default' => 17,
],
...
```

It is also possible to provide a function to calculate default value:

```php
...
'review_list' => [
    'reviews',
    'default' => function($connection) {
        return $connection->createCommand('SELECT value FROM settings WHERE key="default_review"')->queryScalar();
    },
],
...
```

Adding validation rules
-------------------------

The attributes are created automatically. However, you must supply a validation rule for them (usually a `safe` validator):
```php
public function rules()
{
    return [
        [['author_list', 'review_list'], 'safe']
    ];
}
```

Creating form fields
--------------------

By default, the behavior will accept data from a multiselect field:
```php
<?= $form->field($model, 'author_list')
    ->dropDownList($authors_as_array, ['multiple' => true]) ?>
...
<?= $form->field($model, 'review_list')
    ->dropDownList($reviews_as_array, ['multiple' => true]) ?>
```

Known issues and limitations
----------------------------

* Composite primary keys are not supported
* Junction table for many-to-many links is updated using the connection from the primary model
* In the one-to-many relationship (on the `hasMany` side), 


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist voskobovich/yii2-many-many-behavior "*"
```

or add

```
"voskobovich/yii2-many-many-behavior": "*"
```

to the require section of your `composer.json` file.
