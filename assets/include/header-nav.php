<?php require "./DAL/db_config.php"; ?>
<?php require "./auth.php"; ?>
<?php
// Assuming you have a PDO connection instance $pdo
try {
  // Prepare the SQL statement
  $stmt = $pdo->prepare("SELECT * FROM login_users WHERE user_type = :user_type");

  // Bind the user_type parameter to the SQL statement
  $stmt->bindParam(':user_type', $_SESSION['user_type'], PDO::PARAM_STR);

  // Execute the statement
  $stmt->execute();

  // Fetch the user's data
  $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if a user was found
  if ($user_data) {
    $profile_pic = $user_data['user_image'];
  } else {
    echo "User not found.";
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="index.php" class="logo d-flex align-items-center">
      &nbsp;&nbsp;&nbsp;
      <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAIgAkAMBIgACEQEDEQH/xAAcAAACAwEBAQEAAAAAAAAAAAAABgQFBwMCAQj/xABBEAACAQMDAwIEAwQHBQkAAAABAgMEBREAEiEGEzEiQRQyUWEHFXEjQlKRFiRigYKhwTNDouHwJTQ1U2Nyc7LS/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAH/xAAWEQEBAQAAAAAAAAAAAAAAAAAAEQH/2gAMAwEAAhEDEQA/ANx0aNGgNGjRoDRqFeLjFarfJWTJJIFKokcYy0jswVEH3ZmA5455IGky60V1uHUEFLX3CST+ptNWW+I4pAHYLDGw4aRcpIzMTkrGw2qH26Bpg6psFRFVTQXmhkhpAGnkWdSkYPAJbOOSNQazra12+EVVzhrqGhZwkdVVU5jEhP0Q/tB/iQfXxzpQspoeoL1e7ra+5dEtqxUdLHHUKJJ5PUXqOcBSdxRHBGFDbSoxi8ku1r6YSpqauzE1wiM9VJB2Ds/hTO5cEltqLjLeojcdx0DjRVtJcKZamgqoamnfO2WCQOpxwcEca83Cvo7bTGpuFVDTQAgdyVwoyfA59z9NKNju1F+Y1t3SKaeprFUdi008k8AC55aZVEckv1OeAAo8Et7vNzgqZ6K5ihuFFX0DP2DW2+VoSrAB1dkVhGCAP2nlce43KwdYev6GqrZqWht1bO8MrRsO5BG5x+8sbyK7A+xC8/rqwl6z6fiWmL153VEjxJGsEjSK6gFldAu5CAQSGAxnVEnVdJ1JaUcU1HHPGSUmkuCiKCdWKFRMFbDYORlcOjcblLAUfU1XTWOpst9u8ENNP32o6iCnqA/fo3UHuR49e1JNpXwR7bcgANUpqiGqp46illjmglUNHJGwZXU+CCPI101nlLbpB1FTTW29VEUddTO9NLFIWpmnUhi5iGEfuo+5gAPUjkEE5DlY7k1xpnFTGkFdTyGGqgV93bcfQ/wsCGUkDKsOB40Fjo0aNAaNGjQGjRo0Bqpv/UdssMWa+qjWoeNmgpt4Es5UfKgPkk4A+5A1YVlTFRUk9XUvshgjaSRj7KoyT/IaRbpElHaLXcr6vbrK64xVlc7jJgWFHqBHxn0xrGVGPJ3HyxyHO400Frvlmu3Vdar1VPFU19TMWJigwEjEUSfw5nGMDcxVSSTxpZud5g6ypbiv5illFXWxmaWpaRNlAqFFVmC9slnMp2Fsckc411ulzq+pa6prJo2tqVlLFT0y1OQI6V237mY+hScdxzn5UjjUl2yrpY7hYrPaY7X0bGt2lHA+EYOjyYGXmnUbFPAJJ5xwqnhdAh3MWKmsaU/TH59PUW9W7FRQzwum5zwsksbZAdivoUhm4AHjTp050T3lp7j1aiVNWqqYbcXMlLRnAyVU8M5OSWOcZwM43GXbbZu6ijpqyQVE1Ev5lVy7cCWqm3RxkDPCpHG6gHPBTkkZ01yzRxAGWRUz43HGdBmXUvXdyfqqt6btVvi2USgy1Elb2ABtU8nbwMuo++dLll60u9bdp7ZUJU0dXRb3kzVd0NsYBkYY+h4IPkffUK5ziL8ca490JHNJ2j6sB91OAoP6tsx98a+W+lA/E65BGzJWUUsywthXR5MN2mBPzfb6H+VGr33oyiucguVuY268YyaqnZoxUD3SYIyl1PHuDwOfY5/ZYLVSpW0XWNlvaVs9SaWrqqermliqZAoZVIhIblTlQynjncTnWt2ysWW3wSVDJHIyAlGIBX7ao+q7dFNX0NYJJI4qxko6iaJ8GMhi9PKD/EsuFAwQe6c5HGoM+glpui6BmoJa+qgguaVdAKqhqIQvoZJY2eRQmSjPgqOTyfs00Udq6o6hkutueSjrLhblemqlTbPTy08hWTd7EftYQVOVYL7jGrqvvVLBQNbut6UQrN+xeYQM9JUZ8EMM7M/wvgg8AtwxQLZUTWuuo4ekqk3o0Ekwp0jcyRvC4DsjSLlV3BV+bBWVAduyT0hpHTvVNHdmWhnkSG7xBkqaXBwsiHa4Vjwwzz5ztZSQMjTBpEsccHVNBeaygb4dqyphuNumZfXTyGmiVWI+oZHBGcEZHIOm6yV5udoo65ou09RCrvFnPbYjlc/Y5H92gm6NGjQGjRqLdK6K2W6prpld0gjLlIxlnwOFUe7E8Ae5I0Cf+I1fcKq1XihtMix09BSd65uUDGRGBzCpPhu3uYnyMx4+bIruv+oWmv1Nb7fv/wCyy0lQ/ZD5kkiKKiA8M2yU4B4LyQqfmOrrp+qil6Iul2uELRGqkrairSRcuoV3TYw+qoioR/Z0tWi21l6utupYoqKnqLdTRmvuJTvTd4LGjsgY7NxkgKAsrYMDHjgELyhsFj6Xo46++U6Vl2nYGKLaalw+P9nTq2ST9X8tyzEDwy9N0M9Hb2krlRa+slapqgmCFdvCZHnYoVAfcIDqiTp+3U/W1takjllrKeGSrqqyed5ZcFTFHGWYnCtvkYKMDMXA05aCjsW7886kL5z8bFsz/B8NF4+2d3+esde5Xe99Z32lqLmkcNDLKqYoYJpAgk2jG8fKoO5jngDWv1DLber4Z5OIbtAKXcTwJot7oP8AEjSc/wDpge+sOgnt1J+IvUU1fNBGxlqUi+IYrEdzbX3EAnOxnwMcnVHe32+g6k6huFD1DSGSuoZFp5Kykm7QmxKsIJj2kA7ceMfKONTerunrT0T1HaaKy0rSVlQwaCqrqgtHTSFwA+0AA4JyM8D768/h3/2315fprflo6iYVCs4x+z+KV8/rtycHU78d0gk6tsEdWzLTvHtlZOWVC4DED3OCdBS3d6619RWuhrZaeqhuDqr5t6U08eZNhb0jI5wVOefpjzqkm7+gFCJpC0i1dEN2PLCqixx+uNY/1FbKK1dcWKGkjljdqiIzI8rSAMtQYxtZuT8h8/b9NbLSq1T+RWUISEIuFWD+7EhzED9C0uxgD5Eb/TUDDfrc10tM9JHL2ZjteCbGe1KjBo2x74ZVOPfGNLNfQ2XraEUV3oYaLqGmXudiojR5YGHG4Z4lhJ/VT4OGGA7aUbrYrTVdYhrpQQT/AJhRgQzsmJIZYWJOyQepWZZARtII7ROgV+lbzNY+qe3dlk21/ZtzMzF2hlRpFiUnyRnuJu8kCNz85xfdI1Fbb3jmqp5JrXeK+q+GVlUCkJmkePBAyVkXJyTwdoHzcV10tcnTfUq1Mvw1bRVwUCoq6VXqYNrL5m4L7MJIN+47Ym5BUE2LHs/hHCx9VRbrbGwAOcVNMAcfqJI8fqNA8aNV1guZu9qirHp2ppiWjnp2YMYZUYq6ZHnDKRn3HPvqx0Bqj6oxJLZKSQAxVNzj384/2aPMv/HEmrzSh1U1yq7uIrXM4/J4I7hLSpGrGsLO22MEjKnbFKOCOXGfGgqL3JVUQr7Cq4ozdVrKmR1JVKCUPPKWI8KZI5kOfYge4176KuppKOZEQ3W8SskLpRQy9pWRfUZJnRQhLtI5B5G/ADEcwOteorfNdFrVzLbk6f8AiJHXjvQT1MK7cHn5Ubg/xanXuvqrT0I9qoSq9QVdBNW1Kgn+rb90k8rEfKNzOqfViMcA4AtvVNNY7Wt4vW6qul5xUGOlZWCRciBF3EEIVDEZ8t3D5ONNdi6ntF9Oy31W6bZvaJlKsBxn7HGRnBPkay3quaz1d4nkpIC1PStGrOkm5BAI1GIivEeMNnI8gYOTwupVVTFaqGr+EnozsURP2ZHTdksfUqgHceRk5yCQCNWD9A3a3Q3W3y0VQZFSTBEkbbXjYEFXU+zKwBB+oGk+O19PRVvw3V9jtaV8r7UuMlMghr2J4wx+WQ+8Z5znbuAzpkstza6WSnrKaqp5pWhRn9JGG2gkMAcqfPHt99TWaCsgkp62BGSQGOWKVQyMDwVOeCDn38/cag42qxWmzmRrVbaWjMmN5giCbseM4/U65Xfpqy3qojqLrbaeqliXajSrnaM5xqJ/RqghcpaZ7jbyONtHVMIY/wBI2zGP0C++uJsa93t3e53mpQ4KOa4xI31BEITx9xjHPscBH6ip+nqq8Rqtpp7t1BGFEUSjJgxyrSvyIl5JyeTztDHjV7ZLWbfFLLUyCevqmElVOFwGbGAqj91FAwo+nJySSe9BRUNrpFgt9NBTU45CQIFUk++B5J/z11aSdkZool8ZUSMV3H78HA/z+2g7aSeoL7Q3yO6220ST/ndoV6qjZBjdPGp4Q/vDLFGU+QWHjnTLV3Bo7fVPH2oaqOJygqHwgcKSAzew/wBASOOdZJ+FtfU1HWQmqXDyVKymTAABLZcsMff6D97QNPU1zFz6fp0vVI8chw8NRTQzVNHWCVGiIDxKXTekxA3AEMwxvxzEs35jMfySUiWhuNwiqYZ1BUlUAlrMofUi99TGQ2CGmx9tSuk6r8w6Rg6fu7Ck/NKFntskZwDE6kiNSfEkQIGP4VVhn1Yruj7mkV4tl1rVenFRR3X4xZeFpWSqWVyfoMuw/uGfGgebF6b31HGPl+Mjf+808QP/ANQf79XmkqwNcaW+U9wrZJoouoWmkaikQL2HVU7AxjIfsxtvBPzDjxp10BqhiK03XFV3WCmutsIgH8XZkl7n8u/H/P7avtLl5udHX1DW2goPzevgcE9tgiUb8jc03+7YA+FzJg5C40CL1xUVHTV3vKUIheklghqVFRTLKKN5ZSXaMt49UPd2nILc4GDlwvtqpLH0hdYaJJJq24RfCmomfdNVTSfskLufPLj7KPAAGNUfUFhuEMENz6ppIeoI6WnZapreGpp2QxurbkLbZQBIxBBQryQOSNMdJT1F7qKO8XRBHTQEy2+hgmWTeWUjuyMPSx2sQACVAJOWyNoZp1V0xPZ7327aJlo5ozLTkSA8L5yzHkgkgZ9mXzzpTNK8VRJEsYDR8OHUkBVHglSfGMkDJ+n01tv4gWevuVDRfCPM22ozURxDO5Sp9iP7v8WT40nT9K1dzm+FttCI6iikLT1k8iSvDUHa/bLYBkG2TcDjg4yRyNUOf4b22io7PVSUNU9SlTUEyh0C7XVQjAgAeducn6j9SytEso7Mud4GA38S/wCvtkf8tVXR/Ty9OUFRTq5PeqXl27ywQE4UZIBOFA5P+mruRN68cMOVP0OoPMDYAiKBGUcKvjH2/wCuNe5I1kXa6hhwefqPB14wJkw2VZT7HlTr6khztkAV/wDJv00EdIlpZmkcbkI4kJ+Qff8A/X8/GdTNGuRjZG3RNgHko3g/p9P+uNAmfixdkt/T4pVB+IryYQysAViHL+3IIwv+IfbWc/hrKY+sLcxMgJLoBJ7ZVuMHGPsQffHvpy/F6mir6mw025Y6udpUVX9kO3JJ9hu2/wA9IXSlYKW+2+smXcsLEScElBtK+B+uPt9BjVwa903baGv6ZexXClV0ttRJSmJshowpzEwbghu20bBhgjd7HSdaaqrvPUNvs1yqZprdR3WZY1YjNSsbVJj7reXwadTzwShLbjjD5WU1TM8N/wCm2heonhTuQTsUjq4vK5IBKONxw2D5IIPBVYtVDV3qtqq7py10VgcK1M11kUVTMwdi6wR5Chd5bLn5j+6fIgabkfjeqrRSxEH4ASVs5B+TKNFGp+m7uSEf/GdX2lWz1cPTMK0F5pvgzJJ/4jvaSGqkOBueRssjngYkP0VWbGmrQZR+IPUl6/pBUWqnlgit1M6b4VaRHqgUVirSIQyD1Y9P05yDjTr0RfLXeLSI7ZTJQPSYjnt4VVNMTyAAOCp8hhwR98gKP4idL3JbtNe7dDNXU9Rt+Ip4hukhZVC7lXyykKMgcg8854UrD1HBY7pFdUrYV+GParKdpAJJIC3qUoedyH1jjPBHvqjb3vFsS5JbHuNIte43JSmdRKwxnIXOfAJ1V2aNbLfZ7MOKSojaqt6+0ShgJYh9ArOrD7OQOEGkIRdQW2SazWawWy5zNXfH11RTKI+0TL3VTLHaW24KndlVK+kHBL11pGZaC23CmqXh+FroZBUQBGZEkzCzDcGXAWUsSQRgZ1Ay6XejBlb3MWDPLeKksQP4SEA/kg1UdZ9IUNzs5bqjqS7yUFK4mJYUyBD4ydsI459+NKttoujYEjmo/wAQbhQQpCkkUcF2iQlQAfXGEwGHjbyTjn30Gx6NItVeLfS28XCTr6vajaoNMs8S0bxmQIXwGEGPlH188eeNcYL7RVV3ittF13WVMkrKqPFJQSByc8KFiLcY5JAH3OgfWjBfeuA/jOPI+h16xlcMB9xpUurRWd4Y7r13W0bTKzRfEmijD7cZwWhAz6hxqJLcab8tlr6bruqqKaCWGOeaN6IxxdxwuS4hxxuz/L66Bz7ZX/ZNt/snkf8ALXtd2PUAD9jnSJb75brpdo7XbOvbjV1Um7Cwx0jY278nPYx+4f5qfDKTOss0N97n5R1zcKztIjyGBaNtofO3P7Dg+k8Hke+gifi1bamtsNPPSLk084M2PPaIIOPvu2Hj6e/jWW0SC89Uxq0bKK2pIZNu5RuPKnAzjwM8cZPtrXHjMz1EEnVl6CxMiPupqTaxeRolA/q/PrUjVB0fZKaG49u1366Q1NVTiqqGElLM6lljbYxaAkfOOM4xg+/FDl1LNLUPS2Kkdo5rjv70qHDQ0yY7jKf4juRB7gyBudp1NttZa2hkprbNTCOhb4d4YiFEBXgIV/d8cfbxqn6apZh1Fe557hUV/wAOIaNZKlYwykKZWx21UYPeQeM5X7aVbRF01db4lZf6aumu1VcpGtkk9PUrEI1kZ4u03yYKrvODzk+dQP8A1Dd6GyWieuuZJp1G3tqu5pWPARV9yTxj/TWe9C3uvfqiKlp1iorTUl8WwBpFp8IzDa5b0nIGVUbAPAz6tU/4hdR0tz6imWathSktsjU1PE0oBMo4lkK8HOfQPPCsR82rP8N7Ndai+U91ekkpqCnDEyVEZRpyyEAIpwcDcDuPHAAzyRRrOo1bQUdfGY62lhnXBGJYw3B8+dSdGoMtqoaqDpiyi128VNbEotNTI9TOqLLBlB3IoyBIrMrqC5A9aZ4J0wdH7KizVnSF4QmWhgFO8UjIHkpXXCEiNiF43LjOcKGONw1KvEUdpuFRPNJJFarxthq5I5CjU1QQI0lDA5UMNqEjwyofdiKG5W+KzVlPS2Ck/L57UhqJ7xXThIHSQrvMnP7ZpO3tJbG3G7IwuQj0l3vNNcZrNdr1chXwMY17L0Smf+BgrxA4ceMFsE4zkNiU1wuYrlo6m5XoTSYelh32/uyDJB9JjHIAY8E+DyNSbxRRdb2SK82CTs3OD9nuUlVl2n1w7yu1gGLbXwQGzjy2VymqKSeFqeevgtlfCqCUVlVHDOkiliW2Nn2wWJXG3lBoLRq6rrQ9JcLneH7sQnip45aBnmhPO7b2xkY9uRw3Jxz1jutz71RSzXa8fG0+ZJIIZbexSLaDvYGIEDlc8eGB99U/dttZTJDBe7ZQSRYPxH5lCzMQCpOCWGw4UngHdj2zomntlTCXW7WmgZMMsf5lEXYbFyvJYbG9RBzuyRyBoLaO63Znlp/zW7NVwOY5Yo5redjlgsef2WQGJPtnxgH2+01yu1dFLFS3O8T1kTduaFJbeVjbJ4Y9rIJAbAx8wI4AzqqnqLdURRVUd0t1OsBy8K3KE90Fs9qQknGRnaQQSAxJBxr2tRa2kiq0uts7fcCtQJcYSZlLHAOWzvA28BgMDyechOiudzrqHNFdbtMXHbOJ6AR90KzOm5Ys5CjI4zjzjXyG5Vy2lZbPdLpJSwR8MJbeIY40A3YZYj8uR7Y2++eNVpqLWvw9Sb5bpUWICWkjuVP+1O0gMckYcE5YAheBgHnXuept6zy1SXm2TiZW/q1PcocI2zPdXJBOCDnLEZzwQAQEicsIWuNPWXWSkd0nkqZJqBUcK4wQRGQSsjE44GefJ13rr3d4aWOekuF6nWRkEYaSgBlMhIi2bYWzuIIJOAOeSASK6SrtVDUPMLzbKuN23R08NdHIIiGBwig73DD049THnA8asrNTxdNxSdS9Td9JGL/llqA7skKkEkKoGS5GfOSikgt8x0Ezqm4Q9FdDw2+puKpcLgWharIbPdlJaafavqwCzMAB5KjjVd+Hte8Nne6mqmi6ctlCz/CyPTziKXG9tjxjuAKuRtfDesZ8HUJK+637qKvo6uzWm6Ut2pjHR1EhkWIxRk7ijiMkqrOOTt3NhlOGXDbT0UdVUUvT9K2+itbRz3Oo2AfEzj1KhI/fLYlf/CDkOcBZ9IWaO02C3RzU0SV4pk+JkCgs0pGXJbycsWOrzRo0Bo0aNBzqIIqmCSnqYklhlQpJG6hldSMEEHyCNKN0pae1pFS35e9akLfA3OZVka3kqV2ylgRjBwJGyD4fnDO5a+MoZSrAFSMEHwdAm12eiehKK0Wpt9cyJRUk+wqhqJCF7rnDKo3MXw3nxznS9X3u1V1zmoLxLObnRZX86tKMoljQofXGrEsnccoU9YyHPp52uE1iqrbFKli7E9DID3LPXMTCQeCInwe0P7GGTjAC5J1U0FNYl6np6qV6q0V+AqWyuRFR2Cui9piCCAJG9ML49XIBJyFLVHqA0yTy1FRfLM2ZUq7FJExYjKqoi27gPY4d/lJznxHa/wAtZXSrU3GrSKFDPFHPVUdNUtIFTKyRPt2qAHJ+wzzqTd+mLpZ7DZYojLUXOFYoqi40sEhEEMMbbIVWJhLtZyASvJ3OTgbVBbb5DMFs9ZWfGmquoo4orzCJHpoVjzvkUqrb5GBChz++MA7SCHRayvmrWM1VWyxwzRCkaOuoe4zTYcbgCF3Y5Ucg8YyudFPV3D4l62vqqyOOHcKeaKuoT+wV2VpnDekbcge/JOfbVI9Vm8VNrtvR3T9bUQd3tmC2dtanZKqbhIG2oAGbJOQGiIzlgFDd7RcLHDU23pWyxVUVrqKyr+NtfdjR4grdpeVxuDbxycKVODnQT47tNSuVutwqKSsjZqYolxonaOMIMenOS5OARtJwSRzjUy2f0pvCGqo4LnS1dSGWaWuKQRQjAAwjRb3zjnaoHA9QydVX5veIoLxb7BT26JkojKtLaUiirIG3vsYKpZZBtEZZQc4lGOfLr0vapLf1Lc3amqqineKJqW5VTs8qgqu+EtId5G5Q4AG0FmHBGNBSSVFD0/W1dTVVrX290E8ctxMzMgooGC754KcAjCowyV55bLex+2m13Ca6zXuzXdLtcqes+HernlVoamjkRJNmE4jKEgjYOSMkYfiwu1BYIeqZ6yvqDVXQzx1NLSUCOauLEXbIYoS2xuMk7V8BiRqzprXca+BKeaNLFaV+W30LBZnGc+uROIwc8rHk5/f8jQRqCGmgess/RwEQNQ7Vtap3RUbMcskYPp7n0QDanzNz6XZrdQ09to46SkTZEmfJLMxJyWYnksSSSTySSTr3R0lNQ00dLRQRU9PGMJFEgVVH2A8a7aA0aNGgNGjRoDRo0aA1yqqaCsp3p6uCKeCQbXilQMrD6EHg6NGgpx0xBTHNouFxtYz/ALOmnDxAfRYpQ6IP/ao14lt/UDQvBPX2mvp2Xa0NTb2UyD6MwkK/8GjRoI4oLmH3npjpxn+H+F3isYEw/wDl/wDd/k/s+NcRZakLGo6P6WAiz2x8UfRlQpx/VuMqqj9AB7aNGgnQ0XUKoqwVFkt8YGOzFRSTbR9m7iD/AIde/wCjj1IxdrzdK1T/ALtZhTID78QhCRz4YsNGjQWdvt1DbKf4e20dPSQ5z24Iwi5+uB76laNGgNGjRoDRo0aD/9k=" alt="logo">
      <span class="d-none d-lg-block text-success">
      Quetta Land-Use  Dashboard 
    </span>
    </a>
    <!-- <i class="bi bi-list toggle-sidebar-btn"></i> -->
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto me-5">
    <ul class="d-flex align-items-center">

      <style>
        .navbar-nav .nav-link.active {
          background-color: #28a745;
          /* Green background */
          color: white !important;
          /* White text */
          border-radius: 5px;
          /* Rounded corners */
        }
      </style>

      <?php
      $current_page = basename($_SERVER['PHP_SELF']); // Get the current page name
      ?>

      <!-- Custom Navbar Section -->
      <nav class="navbar navbar-expand-lg navbar-light p-2">
        <div class="container-fluid">
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 p-2">
              <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?> fw-bold" aria-current="page" href="index.php" style="padding: 6px 12px;">
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'gis_viewer.php' ? 'active' : ''; ?> fw-bold" href="gis_viewer.php" style="padding: 6px 12px;">
                  GIS Viewer
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'parcel_details.php' ? 'active' : ''; ?> fw-bold" href="parcel_details.php" style="padding: 6px 12px;">
                  Parcel Details
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link fw-bold" href="logout.php" style="padding: 6px 12px;">
                Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Custom Navbar Section -->
    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->