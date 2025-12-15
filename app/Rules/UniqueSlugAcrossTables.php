<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueSlugAcrossTables implements Rule
{
    protected $currentTable;
    protected $ignoreId;
    protected $tables = [
        'posts',
        'pages',
        'categories'
    ];

    /**
     * Create a new rule instance.
     *
     * @param string|null $currentTable The table of the model being updated
     * @param int|null $ignoreId The ID to ignore (for update operations)
     */
    public function __construct($currentTable = null, $ignoreId = null)
    {
        $this->currentTable = $currentTable;
        $this->ignoreId = $ignoreId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->tables as $table) {
            $query = DB::table($table)->where('slug', $value);

            // If we are checking the current table and have an ID to ignore
            if ($table === $this->currentTable && $this->ignoreId) {
                $query->where('id', '!=', $this->ignoreId);
            }

            if ($query->exists()) {
                // If it exists in any table (considering ignore rule), validation fails
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Đường dẫn (slug) này đã được sử dụng bởi một bài viết, trang hoặc danh mục khác.';
    }
}
