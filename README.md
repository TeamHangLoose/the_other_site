the_other_site (of life)
_____________________________


__In a few months there will be a site.. Like nowhere else before ... __


INSERT INTO role (id, parent_id, roleId) VALUES (1, NULL, 'guest'); INSERT INTO role (id, parent_id, roleId) VALUES (2, 1, 'user'); INSERT INTO role (id, parent_id, roleId) VALUES (3, 2, 'admin');

doctrine-module orm:schema-tool:create
doctrine-module orm:validate-schema