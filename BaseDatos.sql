Create table cursos (
    id_curso int AUTO_INCREMENT primary key not null,
    nombre varchar(50) not null,
    capacidad int not null,
    prerresquisitos varchar(200) not null,
    descripcion varchar(300) not null,
    cupo_disponible int
);

create table asignaturas (
    id_asignatura int AUTO_INCREMENT primary key not null,
    nombre varchar(50) not null
);

Create table recursos (
    id_recurso int AUTO_INCREMENT primary key not null,
    nombre varchar(50) not null,
    descripcion varchar(100) not null,
    estado ENUM('uso','libre','roto') not null,
    tipo ENUM('interno','externo') not null

);

create table horarios (
    id_horario int AUTO_INCREMENT primary key not null,
    hora_inicio date not null,
    hora_final date not null
);

create table espacios_fisicos (
    id_espacio int AUTO_INCREMENT primary key not null,
    id_recurso int, 
    foreign key (id_recurso) references recursos(id_recurso),
    capacidad int not null,
    equipamiento varchar (50) not null,
    tipo ENUM('interno', 'externo') not null
);

create table superUsuario (
    id_superusuario int AUTO_INCREMENT primary key not null,
    nombre varchar (50),
    apellido varchar(50),
    nivel_acceso ENUM('1', '2', '3')    
);

create table profesores (
    ci_profesor int AUTO_INCREMENT primary key not null,
    nombre varchar(50) not null,
    apellido varchar(50) not null,
    email varchar(50) not null,
    fecha_nac date not null,
    direccion varchar(50) not null
);

create table profesor_dicta_asignatura (
    id_dicta int AUTO_INCREMENT primary key not null,
    ci_profesor int,
    id_asignatura int,
    foreign key (ci_profesor) references profesores(ci_profesor),
    foreign key (id_asignatura) references asignaturas(id_asignatura)
);

create table profesor_solicita_recurso(
    id_solicita int AUTO_INCREMENT primary key not null,
    ci_profesor int,
    id_recurso int,
    FOREIGN KEY (ci_profesor) references profesores(ci_profesor),
    FOREIGN KEY (id_recurso) references recursos(id_recurso)
);

create table cumple (
    id_horario int,
    id_dicta int,
    FOREIGN KEY (id_horario) references horarios(id_horario),
    FOREIGN KEY (id_dicta) references profesor_dicta_asignatura(id_dicta)
);

create table ocupa (
    id_dicta int,
    id_curso int,
    FOREIGN KEY (id_dicta) references profesor_dicta_asignatura(id_dicta),
    FOREIGN KEY (id_curso) references cursos(id_curso)
);

create table su_administra_horarios (
    id_superusuario int,
    id_horario int,
    FOREIGN KEY (id_superusuario) references superUsuario(id_superusuario),
    FOREIGN KEY (id_horario) references horarios(id_horario)
);

create table su_administra_recursos (
    id_superusuario int,
    id_solicita int,
    FOREIGN KEY (id_superusuario) references superUsuario(id_superusuario),
    FOREIGN KEY (id_solicita) references profesor_solicita_recurso(id_solicita),
    hora_presta date not null,
    hora_vuelta date not null
);

create table su_administra_profesores (
    id_superusuario int,
    id_dicta int,
    FOREIGN KEY (id_superusuario) references profesor_dicta_asignatura(id_dicta),
    FOREIGN KEY (id_dicta) references profesor_dicta_asignatura(id_dicta)
);

create table su_administra_espacios(
    id_superusuario int,
    id_espacio int,
    foreign key (id_superusuario) references superUsuario(id_superusuario),
    foreign key (id_espacio) references espacios_fisicos(id_espacio)
);