<h2 align="center">Sistema de Ordenes de Servicio</h2>
<img width="1920" height="955" alt="2026-03-16 11_21_33-Window" src="https://github.com/user-attachments/assets/c8eb1ba5-4c70-4471-a4ed-6b63a745bf04" />
<br/>
<i>El contenido de los contenedores de la aplicación web y base de datos en docker de este proyecto contiene datos de prueba y no representa información sensible de la empresa Interpc y de sus clientes. </i>
<br/>
<br/>
<p>
El sistema de órdenes de servicio se creó para aportar una solución a la generación de bitácoras de visita a clientes en formato físico dentro de la empresa Interpc, se creó en base al patrón de arquitectura modelo-vista-controlador, en donde la toma de contacto del usuario es la vista (interfaz de usuario) y dependiendo de las acciones del usuario en la aplicación, los controladores gestionan la información obtenida de los modelos, esa información obtenida se plasma en las vistas donde el usuario puede visualizarlas. La aplicación web tiene un acceso restringido y se requiere de un administrador para poder crear los usuarios (Técnicos) que son el personal de la empresa. La aplicación web tiene tres tipos de vistas principales: para administradores, para usuarios (técnicos) y el formulario extendido de una bitácora. 
</p>
<br/>
<figure>
  <div align="center"><figcaption><h3>Vista Usuarios</h3></figcaption></div>
  <div align="center"><img width="362" height="883" alt="2026-03-16 11_24_05-sosv5-docker-run" src="https://github.com/user-attachments/assets/f93cd25e-ca00-4de0-bcaf-f64b0d968566" /></div>
</figure>
<br/>
<figure>
  <div align="center"><figcaption><h3>Vista Administrador</h3></figcaption></div>
  <div align="center"><img width="1920" height="957" alt="2026-03-16 10_59_21" src="https://github.com/user-attachments/assets/8b8f6b6f-eee2-4a6a-aa64-fd5b4485c4c8" /></div>
</figure>
<br/>
<figure>
  <div align="center"><figcaption><h3>Vista formulario extendido</h3></figcaption></div>
  <div align="center"><img width="1559" height="1571" alt="2026-03-16 11_00_53-Window" src="https://github.com/user-attachments/assets/55d9b04c-6250-4f68-999d-517ec83efe9f" />
</div>
</figure>
<br/>
<p>
El formulario extendido de una bitácora es visualizado por el usuario cuando este tiene acceso a un registro de bitácora en la sección de "Seguimiento de bitácoras" de la aplicación, en él se escriben los campos "actividades realizadas" y "observaciones" y en esa vista también se tiene acceso al pad de firmas para que los técnicos y los clientes firmen la conformidad de actividades. La vista de usuario es un espacio para que los técnicos puedan insertar registros como contactos de los clientes, el tipo de equipo y los equipos de los clientes para luego crear registros de bitácoras, estos registros de bitácoras tendrán la referencia del id del cliente y el id del técnico que generó el registro, las bitácoras pueden ser de servicio o equipo, si es servicio solo es cuestión de rellenar el campo correspondiente, por el contrario, si es equipo, el registro de la bitácora tendrá como referencia el Id del equipo. También el técnico tiene opciones de seguimiento de bitácoras y la edición de su firma, el administrador también tiene acceso a todo lo anteriormente mencionado. 
</p>
<br/>
<figure>
  <div align="center"><figcaption><h3>Registro de bitácora</h3></figcaption></div>
  <div align="center"><img width="1920" height="955" alt="2026-03-16 10_58_41-Window" src="https://github.com/user-attachments/assets/81c68959-04fd-44bf-a630-84b4df2e3653" />
</div>
</figure>
<br/>
<figure>
  <div align="center"><figcaption><h3>Editor de firma</h3></figcaption></div>
  <div align="center"><img width="1920" height="955" alt="2026-03-16 10_50_19-Window" src="https://github.com/user-attachments/assets/2648dfbc-448f-414d-ba71-b06e5d62e7d7" />
</div>
</figure>
<br/>
<p>
Por último, tenemos la vista del administrador, a diferencia del usuario donde solo crea registros, consultas y actualizaciones limitadas (seguimiento de bitácoras), en esta vista el administrador puede crear usuarios, reestablecer contraseñas de estos, editar registros de contactos, tipos de equipo, equipos y campos de bitácoras, no se optó en contemplar eliminación de registros, en cambio, los administradores pueden activar o desactivar la visibilidad de estos registros en la aplicación, esta visibilidad también afecta a la vista de usuarios. Finalmente los administradores pueden generar reportes en formato PDF de equipos que posee un cliente y consultar bitácoras con opciones de filtrado, dependiendo del filtro proporcionado se mostrará una tabla con los registros de bitácoras que coincidan con el filtro, cada registro tiene opciones de consulta, edición, generación de PDF y visibilidad.
</p>
<figure>
  <div align="center"><figcaption><h3>Filtrado de bitácoras</h3></figcaption></div>
  <div align="center"><img width="1920" height="955" alt="2026-03-16 11_09_46-Window" src="https://github.com/user-attachments/assets/469e98b6-8fb2-41df-b1da-e89d40d3a53d" />
</div>
</figure>
<br/>
<figure>
  <div align="center"><figcaption><h3>PDF de bitácora</h3></figcaption></div>
  <div align="center"><img width="636" height="899" alt="2026-03-16 08_52_45" src="https://github.com/user-attachments/assets/6dfe2253-ff51-462c-a678-3645c4296016" />
</div>
</figure>
