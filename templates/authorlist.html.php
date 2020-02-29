<h2>사용자 목록</h2>

<table>
  <thead>
    <th>이름</th>
    <th>이메일</th>
    <th>수정</th>
  </thead>
  
  <tbody>
    <?php foreach($authors as $author): ?>
    <tr>
      <td><?=$author->name;?></td>
      <td><?=$author->email;?></td>
      <td><a href="/author/permissions?id=<?=$author->id;?>">권한 수정</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>