drop procedure if exists addNoneKeywords;
delimiter |
create procedure addNoneKeywords(IN noneKeywordID int)
BEGIN
    declare finished int;
    declare tempArticleID int;
    DECLARE myCursor CURSOR FOR(
        Select articles.id
        from articles
        where articles.id not in (SELECT article_id
                                  from article_keywords)
    );
    declare continue handler FOR NOT FOUND set finished = 1;
    set finished = 0;

    SELECT id into noneKeywordID FROM keywords where word = 'none';
    Open myCursor;
    article_loop: while (finished = 0)
    do
        fetch myCursor into tempArticleID;
        IF(finished = 1)
        THEN
            LEAVE article_loop;
        END IF;
        INSERT into article_keywords(article_id, keyword_id) VALUE(tempArticleID, noneKeywordID);
    end while ;
    close myCursor;
    set finished = 0;
end;
|
delimiter |

/* execute after creation of both procedures*/
call getNoneKeyWordId(@noneKeywordID);
call addNoneKeywords(@noneKeywordID);
