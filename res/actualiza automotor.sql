Update automotor a  join automotorimportar b on a.dominio = b.dominio
set a.marca = COALESCE(b.marca,''),
    a.modelo = COALESCE(b.modelo,''),
    a.dni = COALESCE(b.dni,''),
    a.cuit_cuil = COALESCE(b.cuit_cuil,''),
    a.nombre = COALESCE(b.nombre,''),
    a.domicilio = COALESCE(b.domicilio,''),
    a.codigo_postal = COALESCE(b.codigo_postal,''),
    a.provincia = COALESCE(b.provincia,''),
    a.localidad = COALESCE(b.localidad,''),
    a.ultima_actualizacion = SYSDATE()

insert into automotor( dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad, ultima_actualizacion )
select dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad, SYSDATE() from automotorimportar
where 0 in( select count(*) from automotor where automotorimportar.dominio = automotor.dominio)