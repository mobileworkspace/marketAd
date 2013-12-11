<!DOCTYPE html> 

<html> 
<head> 

    <title>超市广告管理系统</title> 
      
    <link rel="stylesheet" href="res/css/reset.css" type="text/css" /> 
    <link rel="stylesheet" href="res/css/bootstrap.min.css" type="text/css" /> 
      
    <script src="res/js/jquery.min.js"></script> 
    <script src="res/js/jquery-ui-1.8.16.custom.min.js"></script> 
      
    <style> 
    body {  
        padding-top: 40px;
    }  
    #main {  
        margin-top: 80px;  
        text-align: center;  
    }  
    </style> 
</head> 

<body> 

    <div class="topbar"> 
        <div class="fill"> 
            <div class="container"> 
                <a class="brand" href="index.php">首 页</a> 
            </div> 
        </div> 
    </div> 
    
    <div id="main" class="container"> 
        <form class="form-stacked" method="POST" action="seo.php"> 
            <div class="row"> 
                <div class="span5 offset5">
                 
                    <label for="login_username">用户名:</label> 
                    <input type="text" id="login_username" name="login_username" placeholder="username" /> 
                  
                    <label for="login_password">密  码:</label> 
                    <input type="password" id="login_password" name="login_password" placeholder="password" />
                    
                    <input type="hidden" id="controller" name="controller" value="UserController"/>  
                    <input type="hidden" id="action" name="action" value="login"/>             
                      
                </div> 
            </div> 
            <div class="actions"> 
                <button type="submit" name="login_submit" class="btn primary large">Login or Register</button> 
            </div> 
        </form> 
    </div> 
</body> 
</html> 