<div class="authorlist">
<h2>사용자 목록</h2>

<table>
  <thead>
    <th>NAME</th>
    <th>EMAIL</th>
    <th>EDIT</th>
  </thead>

  <tbody>
    <?php foreach($authors as $author): ?>
    <tr>
      <td><?=$author->name;?></td>
      <td><?=$author->email;?></td>
      <td><a href="/author/permissions?id=<?=$author->id;?>">EDIT PERMISSION</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
