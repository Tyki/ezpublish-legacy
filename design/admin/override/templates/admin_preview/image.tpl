{* Image - Admin preview *}
{let sort_order=$node.parent.sort_array[0][1]
     sort_column=$node.parent.sort_array[0][0]
     sort_column_value=cond( $sort_column|eq( 'published' ), $node.object.published,
                             $sort_column|eq( 'modified' ), $node.object.modified,
                             $sort_column|eq( 'name' ), $node.object.name,
                             $sort_column|eq( 'priority' ), $node.priority,
                             $sort_column|eq( 'modified_subnode' ), $node.modified_subnode,
                             false() )
     previous_image=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                                class_filter_type, include,
                                                class_filter_array, array( 'image' ),
                                                limit, 1,
                                                attribute_filter, array( and, array( $sort_column, $sort_order|choose( '>', '<' ), $sort_column_value ) ),
                                                sort_by, array( $sort_column, $sort_order|not ) ) )
     next_image=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( 'image' ),
                                            limit, 1,
                                            attribute_filter, array( and, array( $sort_column, $sort_order|choose( '<', '>' ), $sort_column_value ) ),
                                            sort_by, array( $sort_column, $sort_order ) ) ) }

<div class="content-view-full">
    <div class="class-image">

        <h1>{$node.name}</h1>

        <div class="attribute-image">
            <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=imagelarge}</p>
        </div>

        <div class="attribute-caption">
            {attribute_view_gui attribute=$node.object.data_map.caption}
        </div>

    </div>
</div>
{/let}