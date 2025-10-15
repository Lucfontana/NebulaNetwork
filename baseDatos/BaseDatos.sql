create table orientacion (
    id_orientacion int AUTO_INCREMENT primary key not null,
    nombre varchar(50) not null
);

Create table cursos (
    id_curso int AUTO_INCREMENT primary key not null,
    id_orientacion int,
    foreign key (id_orientacion) references orientacion(id_orientacion) ON DELETE CASCADE,
    nombre varchar(50) not null,
    capacidad int not null
);

create table asignaturas (
    id_asignatura int AUTO_INCREMENT primary key not null,
    nombre varchar(50) not null
);

create table espacios_fisicos (
    id_espacio int AUTO_INCREMENT primary key not null,
    capacidad int not null,
    nombre varchar(50) not null,
    tipo ENUM('aula', 'laboratorio', 'salon', 'SUM') not null
);


Create table recursos (
    id_recurso int AUTO_INCREMENT primary key not null,
    id_espacio int,
    foreign key (id_espacio) references espacios_fisicos(id_espacio) ON DELETE CASCADE,
    nombre varchar(50) not null,
    estado ENUM('uso','libre','roto') not null,
    tipo ENUM('interno','externo') not null
);

create table horarios (
    id_horario int AUTO_INCREMENT primary key not null,
    hora_inicio time not null,
    hora_final time not null,
    tipo ENUM('recreo', 'clase') not null
);

create table superUsuario (
    id_superusuario int AUTO_INCREMENT primary key not null,
    nombre varchar (50) not null,
    pass_superusuario varchar (255) not null,
    apellido varchar(50) not null,
    nivel_acceso ENUM('1', '2', '3'),    
    email_superusuario varchar(50) not null
);

create table profesores (
    ci_profesor int AUTO_INCREMENT primary key not null,
    pass_profesor varchar(255) not null,
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
    foreign key (ci_profesor) references profesores(ci_profesor) ON DELETE CASCADE,
    foreign key (id_asignatura) references asignaturas(id_asignatura) ON DELETE CASCADE
);

create table profesor_solicita_recurso(
    id_solicita int AUTO_INCREMENT primary key not null,
    ci_profesor int,
    id_recurso int,
    FOREIGN KEY (ci_profesor) references profesores(ci_profesor) ON DELETE CASCADE,
    FOREIGN KEY (id_recurso) references recursos(id_recurso) ON DELETE CASCADE
);

create table cumple (
    id_horario int,
    id_dicta int,
    asistencia boolean not null,
    dia ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes'),
    FOREIGN KEY (id_horario) references horarios(id_horario) ON DELETE CASCADE,
    FOREIGN KEY (id_dicta) references profesor_dicta_asignatura(id_dicta) ON DELETE CASCADE
);

create table dicta_en_curso (
    id_dicta int,
    id_curso int,
    FOREIGN KEY (id_dicta) references profesor_dicta_asignatura(id_dicta) ON DELETE CASCADE,
    FOREIGN KEY (id_curso) references cursos(id_curso) ON DELETE CASCADE
);

create table dicta_ocupa_espacio (
    id_dicta int,
    id_espacio int,
    FOREIGN KEY (id_dicta) references profesor_dicta_asignatura(id_dicta) ON DELETE CASCADE,
    FOREIGN KEY (id_espacio) references espacios_fisicos(id_espacio) ON DELETE CASCADE
);

create table su_administra_horarios (
    id_superusuario int,
    id_horario int,
    FOREIGN KEY (id_superusuario) references superUsuario(id_superusuario) ON DELETE CASCADE,
    FOREIGN KEY (id_horario) references horarios(id_horario) ON DELETE CASCADE
);

create table su_administra_recursos (
    id_superusuario int,
    id_solicita int,
    FOREIGN KEY (id_superusuario) references superUsuario(id_superusuario) ON DELETE CASCADE,
    FOREIGN KEY (id_solicita) references profesor_solicita_recurso(id_solicita) ON DELETE CASCADE,
    hora_presta datetime not null,
    hora_vuelta datetime
);

create table su_administra_profesores (
    id_superusuario int,
    id_dicta int,
    FOREIGN KEY (id_superusuario) references superUsuario(id_superusuario) ON DELETE CASCADE,
    FOREIGN KEY (id_dicta) references profesor_dicta_asignatura(id_dicta) ON DELETE CASCADE
);

create table su_administra_espacios(
    id_superusuario int,
    id_espacio int,
    foreign key (id_superusuario) references superUsuario(id_superusuario) ON DELETE CASCADE,
    foreign key (id_espacio) references espacios_fisicos(id_espacio) ON DELETE CASCADE
);

INSERT INTO cursos (nombre, capacidad) VALUES
('1° Año A', 30),
('2° Año A', 28);

INSERT INTO asignaturas (nombre) VALUES
('Matemática'),
('Lengua Española');

INSERT INTO espacios_fisicos (capacidad, nombre, tipo) VALUES
(35, 'General', 'Aula'),
(35, 'Aula 101', 'aula'),
(25, 'Laboratorio de Química', 'laboratorio');

INSERT INTO recursos (id_espacio, nombre, estado, tipo) VALUES
(2, 'Microscopio Digital', 'libre', 'interno'),
(1, 'Proyector Multimedia', 'uso', 'interno');

INSERT INTO superUsuario (id_superusuario, nombre, pass_superusuario, apellido, nivel_acceso) VALUES
(12345672, 'Carlos', '$2y$10$hVNDXu5Z2mLgC1bBGoJGt./fMxBEG/hAa.q378y1ezY52kMdNpQYi', 'Rodríguez', '3');

INSERT INTO profesores (ci_profesor, pass_profesor, nombre, apellido, email, fecha_nac, direccion) VALUES
(26197140, '$2y$10$Urjrq9L9F/cExXM0EzqhvucDWeDfsYqgjb7VQBeCJQH8K0dDSAewq', 'Pedro', 'López', 'pedro.lopez@escuela.edu.uy', '1980-07-22', 'Bvar. Artigas 567');

INSERT INTO profesor_dicta_asignatura (ci_profesor, id_asignatura) VALUES
(26197140, 1);

INSERT INTO profesor_solicita_recurso (ci_profesor, id_recurso) VALUES
(26197140, 1);

INSERT INTO su_administra_recursos (id_superusuario, id_solicita, hora_presta, hora_vuelta) VALUES
(12345672, 1, '2025-09-29', '2025-09-29');

INSERT INTO su_administra_profesores (id_superusuario, id_dicta) VALUES
(12345672, 1);

INSERT INTO su_administra_espacios (id_superusuario, id_espacio) VALUES
(12345672, 1);