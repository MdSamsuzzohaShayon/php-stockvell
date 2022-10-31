### MySQL Database

  - [Many to many relationship](https://www.vultr.com/docs/using-many-to-many-sql-relationships-and-intermediate-tables/), [m-to-m](https://stackoverflow.com/questions/2923809/many-to-many-relationships-examples), [m-to-m](https://dba.stackexchange.com/questions/151904/mapping-many-to-many-relationship), 
#### Stockvell relationship 
 - Insert a member and a stockvell (create a stockvell and add a member to it)
  ```
  INSERT INTO stockvell_to_member(stockvell_id, member_id) VALUES (5, 9);
  INSERT INTO stockvell_to_member(stockvell_id, member_id) VALUES (6, 9);
  ```
 - Populate from relational table. Get all the members of a stockvell pack. [Tutorial wds](https://www.youtube.com/watch?v=p3qvj9hO_Bo), [tutorial traversy media](https://www.youtube.com/watch?v=9ylj9NR0Lcg&t=3640s)
  ```
  SELECT members.id, members.firstname FROM stockvell_to_member LEFT JOIN members
  ON stockvell_to_member.member_id = members.id
  WHERE stockvell_to_member.stockvell_id = '6';
  ```

 - Get all the stockvell that a person member of
  ```
  SELECT stockvells.id, stockvells.name, stockvells.status, stockvells.category FROM stockvell_to_member 
  LEFT JOIN stockvells
  ON stockvell_to_member.stockvell_id = stockvells.id
  WHERE stockvell_to_member.member_id = 6;
  ```
 - Get all collumns of both table - **Get stockvell with leader**, [tutorial](https://www.youtube.com/watch?v=bPeQHlmfPPk&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=21)
  ```
  // INNER JOIN and JOIN both are same
  // Where both table has same properties we could "ON" to join table
  SELECT * FROM stockvells INNER JOIN members ON stockvells.leader_id = members.id;
  // Using name allies
  SELECT * FROM stockvells s INNER JOIN members m ON s.leader_id = m.id;
  // Select specific collumns from both table
  SELECT s.id, s.name, s.goal, s.category, m.firstname, m.email FROM stockvells s INNER JOIN members m ON s.leader_id = m.id;
  // We can also use WHERE clause here
  SELECT s.id, s.name, s.goal, s.category, m.firstname, m.email, m.gender FROM stockvells s INNER JOIN members m ON s.leader_id = m.id WHERE gender='male';
  // Order by for making it ascending or descending order according to specific column 
  SELECT s.id, s.name, s.goal, s.category, m.firstname, m.email, m.gender FROM stockvells s JOIN members m ON s.leader_id = m.id WHERE gender='male' ORDER BY m.firstname;
  ```
 - Left join returns all records from the left table and the match record from the right table [tutorial](https://www.youtube.com/watch?v=sEjnXJOytzc&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=22)

  ```
  // bring all records from stockvell_to_member (left table), does not matter it matches with foreign key member id or not.
  SELECT * FROM stockvell_to_member LEFT JOIN members ON stockvell_to_member.member_id = members.id;
  // here left table is stockvell_to_member
  SELECT sm.id, sm.stockvell_id, sm.member_id, m.firstname, m.email FROM stockvell_to_member sm LEFT JOIN members m ON sm.member_id = m.id;
  // here right table is members - this will display all the columns of right table (no matter stockvell_to_member has a relationship or not)
  SELECT sm.id, sm.stockvell_id, sm.member_id, m.firstname, m.email FROM stockvell_to_member sm RIGHT JOIN members m ON sm.member_id = m.id;
  ```
- MySQL JOIN Multiple Tables - **Get stockvells with all of their members**. [Tutorial](https://www.youtube.com/watch?v=dkY-KvFtNFM&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=24)
  ```
  // Getting all records from stockvell_to_member
  SELECT sm.id, s.name, m.firstname FROM stockvell_to_member sm INNER JOIN members m ON sm.member_id = m.id INNER JOIN stockvells s ON sm.stockvell_id=s.id;
  ```

- "GROUP BY" clause is used in conjuction with the select statement and aggregate functions to group together by common column value.
  ```
  // get all stockvells and their total member
  SELECT member_id, COUNT(member_id) FROM stockvell_to_member GROUP BY member_id;
  // Change table header name
  SELECT member_id, COUNT(member_id) AS total_members  FROM stockvell_to_member GROUP BY member_id;
  // Use where and "join" before "group by" and "order by" after "group by"
  SELECT s.name, COUNT(sm.member_id) AS total_members  FROM stockvell_to_member sm INNER JOIN stockvells s ON sm.member_id = s.id WHERE s.payment <= 200 GROUP BY sm.member_id ORDER BY COUNT(sm.member_id) DESC;
  // approved Working query
  SELECT s.name, sm.stockvell_id, COUNT(sm.stockvell_id) as totel_members FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id=s.id GROUP BY sm.stockvell_id;
  ```


- Sub query or nested query (Works with insert, update, delete, and select query). [tutorial](https://www.youtube.com/watch?v=VxiF_MgePL8&list=PL0b6OzIxLPbzf12lu5etX_vjN-eUxgxnr&index=26)









