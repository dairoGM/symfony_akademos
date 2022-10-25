-- View: seguridad.v_chat_person_users

-- DROP VIEW seguridad.v_chat_person_users;

CREATE OR REPLACE VIEW seguridad.v_chat_person_users
 AS
 SELECT usr.email,
    concat(per.primer_nombre, ' ', per.segundo_nombre, ' ', per.primer_apellido, ' ', per.segundo_apellido) AS concat,
    usr.id AS user_id,
    per.id AS persona_id,
    chat.userchat,
    chat.email AS chat_email,
    chat.nickname,
    chat.password,
    chat.alg_pass_encrypt,
    chat.token,
    chat.error,
    chat.teamd_id,
    chat.actualizado
   FROM personal.tbd_persona per
     JOIN seguridad.tbd_usuario usr ON per.usuario_id = usr.id
     LEFT JOIN seguridad.tbd_usario_chat chat ON chat.persona_id = per.id;

ALTER TABLE seguridad.v_chat_person_users
    OWNER TO api_db;

