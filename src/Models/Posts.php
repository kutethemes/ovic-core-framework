<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Posts extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table   = 'posts';
    protected $appends = [ 'meta' ];

    /**
     * Get the user that owns the phone.
     */
    public function meta()
    {
        return $this->hasMany(Postmeta::class, 'post_id');
    }

    public function getMetaAttribute()
    {
        return $this->meta()->get()->collect()->mapWithKeys(
            function ( $item, $key ) {
                return [ $item['meta_key'] => maybe_unserialize($item['meta_value']) ];
            }
        )->toArray();
    }

    /*
     * @param: $post is name/slug/id
     * */
    public static function is_exits( $post )
    {
        $column = 'id';
        if ( !abs(intval($post)) ) {
            $column = 'name';
        }
        $post = Posts::where($column, '=', $post)->first();
        if ( $post === null ) {
            return false;
        }

        return true;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return array
     */
    public static function update_post( $request, $id )
    {
        $data  = [];
        $input = [
            'name',
            'title',
            'content',
            'status',
            'post_type',
            'user_id',
            'owner_id',
            'created_at',
            'updated_at',
        ];

        if ( !empty($request) ) {
            foreach ( $request as $key => $value ) {
                if ( in_array($key, $input) ) {
                    $data[$key] = $value;
                }
            }

            Posts::where('id', $id)->update($data);

            if ( !empty($request['meta']) ) {
                foreach ( $request['meta'] as $meta_key => $meta_value ) {
                    Postmeta::where('meta_key', $meta_key)
                        ->where('post_id', $id)
                        ->update(
                            [
                                'meta_value' => maybe_serialize($meta_value),
                            ]
                        );
                }
            }
        }

        return [
            'code'    => 200,
            'status'  => 'success',
            'message' => 'The post is updated.',
        ];
    }

    public static function add_post( $request )
    {
        $name = !empty($request['name']) ? $request['name'] : Str::slug($request['title']);

        if ( Posts::is_exits($name) ) {
            return [
                'code'    => 400,
                'post_id' => 0,
                'status'  => 'error',
                'message' => 'The post is exits.',
            ];
        }

        $post = new Posts();

        $post->name      = $name;
        $post->title     = $request['title'];
        $post->post_type = $request['post_type'];
        $post->content   = !empty($request['content']) ? $request['content'] : '';
        $post->user_id   = !empty($request['user_id']) ? $request['user_id'] : 0;
        $post->owner_id  = !empty($request['owner_id']) ? $request['owner_id'] : 0;
        $post->status    = !empty($request['status']) ? maybe_serialize($request['status']) : 'publish';

        $post->save();

        $post_id = $post->getAttributeValue('id');

        if ( !empty($request['meta']) ) {
            foreach ( $request['meta'] as $meta_key => $meta_value ) {
                $meta             = new Postmeta();
                $meta->post_id    = $post_id;
                $meta->meta_key   = maybe_serialize($meta_key);
                $meta->meta_value = maybe_serialize($meta_value);

                $meta->save();
            }
        }

        return [
            'code'    => 200,
            'post_id' => $post_id,
            'status'  => 'success',
            'message' => 'The post is created.',
        ];
    }

    public static function remove_post( $post_id )
    {
        if ( !Posts::is_exits($post_id) ) {
            return [
                'code'    => 400,
                'status'  => 'error',
                'message' => 'The post is do not exits.',
            ];
        }

        Postmeta::where('post_id', $post_id)->delete();

        return [
            'code'    => 200,
            'status'  => 'success',
            'count'   => Posts::destroy($post_id),
            'message' => 'Xóa thành công.',
        ];
    }

    /*
     * @param: $args is array - https://laravel.com/docs/6.x/queries#where-clauses
     * */
    public static function get_posts( $args )
    {
        $limit  = 18;
        $offset = 0;
        if ( isset($args['limit']) ) {
            $limit = $args['limit'];
            unset($args['limit']);
        }
        if ( isset($args['offset']) ) {
            $offset = $args['offset'];
            unset($args['offset']);
        }
        $posts = \Ovic\Framework\Posts::where($args)
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        return $posts;
    }
}
