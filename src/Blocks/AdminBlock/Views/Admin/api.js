export const admin_api = {
    get_panel_summary: ADMIN_URL + '/dashboard/get-panel-summary/'
};

export const log_api = {
    all: ADMIN_URL + '/log/all/',
    list_by: ADMIN_URL + '/log/list-by/',
    destroy: ADMIN_URL + '/log/delete/'
};

export const auth_api = {
    login: ADMIN_URL + '/auth/login/',
    login_as_user: ADMIN_URL + '/auth/login-as-user/',
    logout: ADMIN_URL + '/auth/logout/',
    lost_password: ADMIN_URL + '/auth/lost-password/',
    reset_password: ADMIN_URL + '/auth/reset-password/'
};

export const status_api = {
    all: ADMIN_URL + '/status/all/',
    create: ADMIN_URL + '/status/create/',
    read: ADMIN_URL + '/status/read/',
    update: ADMIN_URL + '/status/update/',
    destroy: ADMIN_URL + '/status/delete/',
    get_id_by_role: ADMIN_URL + '/status/get-id-by-role/'
};

export const account_api = {
    all: ADMIN_URL + '/account/all/',
    read: ADMIN_URL + '/account/read/',
    create: ADMIN_URL + '/account/create/',
    update: ADMIN_URL + '/account/update/',
    destroy: ADMIN_URL + '/account/delete/',
    change_state: ADMIN_URL + '/account/change-state/',
    get_societies: ADMIN_URL + '/account/get-societies/',
    list_between_dates: ADMIN_URL + '/account/list-between-dates/'
};

export const society_api = {
    update: ADMIN_URL + '/society/update/',
    list_names: ADMIN_URL + '/society/list-names/',
    destroy: ADMIN_URL + '/society/delete/'
};

export const address_api = {
    update_or_create: ADMIN_URL + '/address/update-or-create/',
    locate: ADMIN_URL + '/address/locate/'
};

export const website_api = {
    intro: ADMIN_URL + '/website/get-intro/',
    all: ADMIN_URL + '/website/all/',
    get_last: ADMIN_URL + '/website/get-last/',
    count: ADMIN_URL + '/website/count/',
    read: ADMIN_URL + '/website/read/',
    update: ADMIN_URL + '/website/update/',
    get_modules: ADMIN_URL + '/website/get-modules/',
    update_modules: ADMIN_URL + '/website/update-modules/',
    get_summary: ADMIN_URL + '/website/get-summary/',
    change_state: ADMIN_URL + '/website/change-state/',
    change_theme: ADMIN_URL + '/website/change-theme/',
    destroy: ADMIN_URL + '/website/delete/'
};

export const theme_api = {
    all: ADMIN_URL + '/theme/all/',
    create: ADMIN_URL + '/theme/create/',
    update: ADMIN_URL + '/theme/update/',
    destroy: ADMIN_URL + '/theme/delete/',
    change_state: ADMIN_URL + '/theme/change-state/'
};

export const profession_api = {
    all: ADMIN_URL + '/profession/all/',
    list_names: ADMIN_URL + '/profession/list-names/',
    create: ADMIN_URL + '/profession/create/',
    update: ADMIN_URL + '/profession/update/',
    destroy: ADMIN_URL + '/profession/delete/'
};

export const route_api = {
    all: ADMIN_URL + '/route/all/',
    update_or_create: ADMIN_URL + '/route/update-or-create/',
    find_by_name: ADMIN_URL + '/route/find-by/name/',
    get_website_routes: ADMIN_URL + '/route/get-website-routes/',
    destroy: ADMIN_URL + '/route/delete/'
};

export const template_api = {
    all: ADMIN_URL + '/template/all/',
    create: ADMIN_URL + '/template/create/',
    read: ADMIN_URL + '/template/read/',
    update: ADMIN_URL + '/template/update/',
    update_or_create: ADMIN_URL + '/template/update-or-create/',
    destroy: ADMIN_URL + '/template/delete/',
    get_website_stylesheets: ADMIN_URL + '/template/get-website-stylesheets/',
    get_website_layouts: ADMIN_URL + '/template/get-website-layouts/',
    get_website_content_layouts: ADMIN_URL + '/template/get-website-content-layouts/',
    find_with_content: ADMIN_URL + '/template/find-with-content/'
};

export const library_api = {
    all: ADMIN_URL + '/library/all/',
    create: ADMIN_URL + '/library/create/',
    update: ADMIN_URL + '/library/update/',
    destroy: ADMIN_URL + '/library/delete/',
    get_names: ADMIN_URL + '/library/get-names/'
};

export const media_api = {
    all: ADMIN_URL + '/media/all/',
    create: ADMIN_URL + '/media/create/',
    read: ADMIN_URL + '/media/read/',
    update: ADMIN_URL + '/media/update-or-create/',
    destroy: ADMIN_URL + '/media/delete/',
    compress_via_tiny_png: ADMIN_URL + '/media/compress-via-tiny-png/',
    find_one_by: ADMIN_URL + '/media/find-one-by/'
};

export const module_api = {
    all: ADMIN_URL + '/module/all',
    create: ADMIN_URL + '/module/create/',
    read: ADMIN_URL + '/module/read/',
    update: ADMIN_URL + '/module/update-or-create/',
    destroy: ADMIN_URL + '/module/delete/',
    all_by_category: ADMIN_URL + '/module/all-by-category/'
};

export const module_category_api = {
    all: ADMIN_URL + '/module-category/all',
    create: ADMIN_URL + '/module-category/create/',
    read: ADMIN_URL + '/module-category/read/',
    update: ADMIN_URL + '/module-category/update-or-create/',
    destroy: ADMIN_URL + '/module-category/delete/',
    check_update: ADMIN_URL + '/module-category/check-update/',
    get_with_readme: ADMIN_URL + '/module-category/get-with-readme/'
};

export const page_api = {
    all: ADMIN_URL + '/page/all/',
    read: ADMIN_URL + '/page/read/',
    destroy: ADMIN_URL + '/page/delete/',
    update_or_create: ADMIN_URL + '/page/update-or-create/',
    change_state: ADMIN_URL + '/page/change-state/',
    emit_page_update_event: ADMIN_URL + '/page/emit-page-update-event/'
};

export const content_api = {
    update_or_create: ADMIN_URL + '/content/update-or-create/',
    get_page_contents: ADMIN_URL + '/content/get-page-contents/',
    get_global_contents: ADMIN_URL + '/content/get-global-contents/',
    destroy: ADMIN_URL + '/content/delete/'
};

export const custom_field_api = {
    all: ADMIN_URL + '/custom-field/all/',
    read:  ADMIN_URL + '/custom-field/read/',
    update_or_create:  ADMIN_URL + '/custom-field/update-or-create/',
    update_or_create_front:  ADMIN_URL + '/custom-field/update-or-create-front/',
    get_rules: ADMIN_URL + '/custom-field/get-rules/',
    destroy: ADMIN_URL + '/custom-field/delete/',
    destroy_field: ADMIN_URL + '/custom-field/delete-field/',
    admin_render: ADMIN_URL + '/custom-field/admin-render/'
};

