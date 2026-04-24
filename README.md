<h2 align="center">Sistema de Ordenes de Servicio</h2>
<img width="1920" height="955" alt="2026-03-16 11_21_33-Window" src="https://github.com/user-attachments/assets/c8eb1ba5-4c70-4471-a4ed-6b63a745bf04" />
<br/>
<i>El contenido de los contenedores de la aplicación web y base de datos en docker de este proyecto contiene datos de prueba y no representa información sensible de la empresa Interpc y de sus clientes. </i>
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
