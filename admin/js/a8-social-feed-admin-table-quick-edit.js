jQuery(document).ready(function($){
    //only on accounts for now
    let row;
    $(".asf-quick-edit").on('click', function(){
        console.log("edit button clicked");
        row = $(this).parents("tr");
        row.hide();
        let html = "";

        html += '<tr class="inline-edit-row">';
        html += '<td colspan = "3">';
        html += '<div class="inline-edit-wrapper">';
        html += '<fieldset class="inline-edit-col-left">';
        html += '<legend class="inline-edit-legend">Quick Edit</legend>';
        html += '<form method="POST">';
        html += '<input type="text" id="edit_cat_name" name="edit_cat_name" placeholder="Edit Category Name">';
        html += '<div class="submit inline-edit-save">';
        html += '<button type="button" class="button button-primary save">Update</button>';
        html += '<button type="button" class="button cancel asf-quick-edit-cancel">Cancel</button>';
        html += '</div>';
        html += '</form>';
        html += '</fieldset>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        row.after(html);
    });

    $("body").on('click',".asf-quick-edit-cancel",function(){
        let edit_menu = $(this).parents("tr");
        edit_menu.hide();
        row.show();
    });
});