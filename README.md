# Generador de Números  Consecutivos  
![icon](https://bismarcksevilla.com/images/uploads/numerado-pdf.jpg)  

## Introducción  
Una herramienta online que permite configurar a detalle numeraciones consecutivas, genera un archivo PDF listo para imprimir según los parámetros establecidos.

Por razones de rendimiento el PDF se limita a 250 páginas, con un máximo de 8 campos numéricos. Puede clonar el proyecto y establecer su propio límite en su servidor. Se aprecia cualquier aporte :-)...  

**[Prueba en marcha](https://bismarcksevilla.com/proyecto/numerador)**  
**[Más información](https://bismarcksevilla.com/entrada/generador-de-numeros-consecutivos)**  

![icon](https://bismarcksevilla.com/images/uploads/2018/pro-numerador.jpg) 

* El formulario trabaja sobre el núcleo de [Symfony4](http://symfony.com/), por lo que se debe instalar a través de composer.  
* El PDF se genera gracias a [spipu/html2pdf](https://github.com/spipu/html2pdf)  
* Interfaz basada en [bootstrap4](https://getbootstrap.com/docs/4.2/getting-started/introduction)  

## Instalación (dev)  
0. **Requerido.**  
a. [XAMPP con PHP 7.0.*](https://www.apachefriends.org/es/download.html)  
b. [Composer](https://getcomposer.org/download/)  

1. **Clonar Repositorio.**   
~~~  
> git clone https://github.com/bismarcksevilla/pro-numerador.git  
~~~  

2. **Instalar Proyecto.**  
~~~  
> composer install  
~~~  

3. **Inicie el servidor local integrado.**  
~~~  
 > php bin/console server:run 
~~~  

4. **Visualice, utilice o edite el proyecto :-)**  

5. **Recuerda enviar tus aportes.**  

~~~ 
> git add .  
> git commit -m "mensaje que describe el cambio."  
> git push  
 ~~~  

**Nota para usuario de VSCode**  
*El directorio .vscode contiene la definición de tareas necesarias para inicializar el servidor integrado; Instale la extensión  AutoLaunch para automatizar el proceso.*   
