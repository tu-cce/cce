drop procedure if exists getNoneKeyWordId;
delimiter |
create procedure getNoneKeyWordId(Out noneKeywordID int)
BEGIN
	SELECT id into noneKeywordID FROM keywords where word = 'none';
    if(noneKeywordID IS NULL) then
        INSERT into keywords (word) VALUE('none');
        SELECT id into noneKeywordID FROM keywords where word = 'none';
    end if;
end;
|
delimiter |

call getNoneKeyWordId(@id);
select @id;
