function getTextDescriptions(){

	switch (language){
		case "eng": 
			buttonStartLogin = "Login";
			buttonStartNewUser = "New User";
			buttonCollStart = "Start";
			buttonCollReset = "Reset";
			buttonNewUserSubmit="Submit";
			buttonNewUserCancel="Cancel";
			buttonPlayerDetailedHug = "Hug!";
			
			//HTML texts
			textNewUserUsername="Username";
			textNewUserPassword="Password";
			textCollBlocked="Currently unavailable:";
			//textCollSelect="Select the players you want to collaborate with:";
			textGlobalScore="Player Ranking:";
			textRitualsSelect="Select the players you want to perform a ritual with:";
			
			//Toasts
			textStartPW="Wrong name and/or password! Please try again ...";
			textEmoStatementCountA="You stated your emotion! You can state 2 more emotions in the next 20 minutes ...";
			textEmoStatementCountN_1="You stated your emotion! You can state ";
			textEmoStatementCountN_2=" more emotions ...";
			textEmoStatementOut="You can´t state more than three emotions during 20 minutes ...";
			textCollNotReady="Not all players selected you as their ritual partner yet. Please try again in a few seconds.";
			textNewPwMatch="Your passwords don´t match! Please input again ...";
			textNewUsernameTaken="Username already taken! Please choose another one ...";
			textNewChoose="Please choose a username and password!";
			textHugClick_1="You hugged ";
			textHugClick_2="! ... She/He received four flower points (FPs) :)";
			textNoHugsLeft="Sorry no hugs left for today ... ";
			textEmostatementTip="This icon shows your latest emotion statement.";
			textFlowerpointsTip="This icon shows the total amount of flowerpoints (FPs) that you accumulated throughout the game so far. You gain FPs by stating emotions (2), by getting hugged (4) and by successfully performing rituals (10 plus).";
			textHugTip="This icon shows the total number of hugs that you recieved.";
			textEmostatementcountTip="You can state 3 emotions within 20 minutes and the number under this icon shows your currently remaining number of possible emotion statements in this time period.";
			textHugCountTip="You can give 10 hugs within 24 hours and the number under this icon shows your currently remaining number of possible hugs in this time period.";
			textBlockedPlayerTip="In order to perform a ritual with a player a second time both of you have to first perform one with someone else.";
			textTreeCredits="You can let the community tree blossom by succesfully performing rituals with other players. When the tree is fully blossomed the game ends and the player with the most Flower Points wins! <p /><p />Art Design: Mariana Irigaray";
			textCollSuccess1="Your ritual was successful! You gain ";
			textCollSuccess2=" flower points. The emotions used in this collaboration were: ";
			textCollSuccess3=" You also let a flower grow near the cherry blossom tree...";
			textCollFailure1="You failed to perform your ritual! The emotions used in this collaboration were: ";
			textCollFailure2=" and you didn´t had all of them!";
			textCollTip="This icon shows how many successful rituals you performed so far. Successful rituals - you and your partners need to have a specific combination of emotions - gives points to grow the cherry blossom tree. The needed emotion combination changes everytime you perform a ritual and is unknown at the start of a ritual.";
			
			textTimelineEmotionHead="Emotion Stated...";
			textTimelineEmotionAction="stated";
			textTimelineRitualHeadtext="Ritual performed...";
			textTimelineRitualAction="collaborated.";
			textTimelineHugHead="Hug happened...";
			textTimelineHugAction="hugged";
			
			textPlayerListHead="Player overview:";	
			textPlayerListLogin="Last Login:";
			textPlayerListEmotion="Last Emotion:";
			textPlayerListHugged="Hugged:";
			
			textCollPage="Your";
			
			textEmotionList="Your usable emotions:";
			textChargePowerPage="State your emotions here:";
			
			textCherryBlossomTreeLevel1="Next blossom state at";
			textCherryBlossomTreeLevel2="points";
			
			textCherryBlossomTreeLevelLast1="Last blossom state at";
			textCherryBlossomTreeLevelLast2="points";
			
		break;
		case "jp":	 
			buttonStartLogin = "ログイン";
			buttonStartNewUser = "新規ユーザ";
			buttonCollStart = "スタート";
			buttonCollReset = "リセット";
			buttonNewUserSubmit="登録する";
			buttonNewUserCancel="キャンセル";
			buttonPlayerDetailedHug = "ハグ!";
			
			//HTML texts
			textNewUserUsername="ユーザ名";
			textNewUserPassword="パスワード";
			textCollBlocked="現在利用できません:";
			//textCollSelect="Select the players you want to collaborate with:";
			textGlobalScore="プレイヤーランキング:";
			textRitualsSelect="Ritualを実施したいパートナプレーヤを選んでください。:";
			
			//Toasts
			textStartPW="名前かパスワードが違います。もう一度やり直してください ...";
			textEmoStatementCountA="感情の入力が完了しました。次の20分間であと2回入力することができます。 ...";
			textEmoStatementCountN_1="You stated your emotion! You can state ";
			textEmoStatementCountN_2=" more emotions ...";
			textEmoStatementOut="20分間の間に3回しか感情入力はできません。 ...";
			textCollNotReady="まだすべてのプレーヤがritualのパートナの準備ができていません。数秒後にやり直してください。";
			textNewPwMatch="パスワードが一致しません！もう一度入力してください...";
			textNewUsernameTaken="ユーザ名は既に使用されています。別のユーザ名を使用してください。 ...";
			textNewChoose="ユーザ名とパスワードを入力してください。";
			textHugClick_1="ハグされました ";
			textHugClick_2=" ... 相手が4フラワーポイントを獲得しました。 :)";
			textNoHugsLeft="今日はもうハグできません。";
			textEmostatementTip="このアイコンはあなたが最後に入力した感情を示します。";
			textFlowerpointsTip="このアイコンはこれまでのゲームで獲得したフラワーポイントの合計を示します。フラワーポイントは、感情を入力すること（2ポイント）、ハグされること（4ポイント）、ritualsを成功させること）（10ポイント）で獲得できます。";
			textHugTip="このアイコンはこれまでに受け取ったハグの回数を示します。";
			textEmostatementcountTip="20分間に3つの感情を入力できます。このアイコンの下の数字は現在残り何回入力できるかを示します。";
			textHugCountTip="24時間で10回のハグができます。このアイコンの下の数字は現在残り何回ハグできるかを示します。";
			textBlockedPlayerTip="同じプレーヤと再びritualを実施するには、その前に別のプレーヤとritualを実施しなければなりません。";
			textTreeCredits="他のプレーヤとritualを成功させることでコミュニティーツリーが咲きます。ツリーが完全に咲いた時点でゲームは終了し、最も多くのフラワーポイントを獲得したプレーヤが勝者となります。 <p /><p />Art Design: Mariana Irigaray";
			textCollSuccess1="Your ritual was successful! You gain ";
			textCollSuccess2=" flower points. The emotions used in this collaboration were: ";
			textCollSuccess3=" You also let a flower grow near the cherry blossom tree...";
			textCollFailure1="You failed to perform your ritual! The emotions used in this collaboration were: ";
			textCollFailure2=" and you didn´t had all of them!";
			textCollTip="このアイコンはこれまでに成功したritualの回数を示します。ritualの成功、すなわちパートナとの感情コンビネーションの提出、は、桜の木の成長につながります。感情コンビネーションはritualを実施する度に変わり、ritualの開始前には分かりません。";
					
			textTimelineEmotionHead="感情が入力されました...";
			textTimelineEmotionAction="と述べ";
			textTimelineRitualHead="儀式が行われました...";
			textTimelineRitualAction="コラボレーション.";
			textTimelineHugHead="ハグが行われました...";
			textTimelineHugAction="にハグし";
			
			textPlayerListHead="プレイヤーの概要：";	
			textPlayerListLogin=" 最終ログイン：";
			textPlayerListEmotion="最後の感情：";
			textPlayerListHugged="にハグし:";
			
			textCollPage="あなたの";
			
			textEmotionList="あなたの使用可能な感情:";
			textChargePowerPage="ここにあなたの感情を入力してください:";
			
			textCherryBlossomTreeLevel1="次回は";
			textCherryBlossomTreeLevel2="点で開花する";
			
			textCherryBlossomTreeLevelLast1="前回は";
			textCherryBlossomTreeLevelLast2="点で開花した";
					
		break;
		default: 
			buttonStartLogin = "Login";
			buttonStartNewUser = "New User";
			buttonCollStart = "Start";
			buttonCollReset = "Reset";
			buttonNewUserSubmit="Submit";
			buttonNewUserCancel="Cancel";
			buttonPlayerDetailedHug = "Hug!";
			
			//HTML texts
			textNewUserUsername="Username";
			textNewUserPassword="Password";
			textCollBlocked="Currently unavailable:";
			//textCollSelect="Select the players you want to collaborate with:";
			textGlobalScore="Player Ranking:";
			textRitualsSelect="Select the players you want to perform a ritual with:";
			
			//Toasts
			textStartPW="Wrong name and/or password! Please try again ...";
			textEmoStatementCountA="You stated your emotion! You can state 2 more emotions in the next 20 minutes ...";
			textEmoStatementCountN_1="You stated your emotion! You can state ";
			textEmoStatementCountN_2=" more emotions ...";
			textEmoStatementOut="You can´t state more than three emotions during 20 minutes ...";
			textCollNotReady="Not all players selected you as their ritual partner yet. Please try again in a few seconds.";
			textNewPwMatch="Your passwords don´t match! Please input again ...";
			textNewUsernameTaken="Username already taken! Please choose another one ...";
			textNewChoose="Please choose a username and password!";
			textHugClick_1="You hugged ";
			textHugClick_2="! ... She/He received four flower points (FPs) :)";
			textNoHugsLeft="Sorry no hugs left for today ... ";
			textEmostatementTip="This icon shows your latest emotion statement.";
			textFlowerpointsTip="This icon shows the total amount of flowerpoints (FPs) that you accumulated throughout the game so far. You gain FPs by stating emotions (2), by getting hugged (4) and by successfully performing rituals (10 plus).";
			textHugTip="This icon shows the total number of hugs that you recieved.";
			textEmostatementcountTip="You can state 3 emotions within 20 minutes and the number under this icon shows your currently remaining number of possible emotion statements in this time period.";
			textHugCountTip="You can give 10 hugs within 24 hours and the number under this icon shows your currently remaining number of possible hugs in this time period.";
			textBlockedPlayerTip="In order to perform a ritual with a player a second time both of you have to first perform one with someone else.";
			textTreeCredits="You can let the community tree blossom by succesfully performing rituals with other players. When the tree is fully blossomed the game ends and the player with the most Flower Points wins! <p /><p />Art Design: Mariana Irigaray";
			textCollSuccess1="Your ritual was successful! You gain ";
			textCollSuccess2=" flower points. The emotions used in this collaboration were: ";
			textCollSuccess3=" You also let a flower grow near the cherry blossom tree...";
			textCollFailure1="You failed to perform your ritual! The emotions used in this collaboration were: ";
			textCollFailure2=" and you didn´t had all of them!";
			textCollTip="This icon shows how many successful rituals you performed so far. Successful rituals - you and your partners need to have a specific combination of emotions - gives points to grow the cherry blossom tree. The needed emotion combination changes everytime you perform a ritual and is unknown at the start of a ritual.";
			
			textTimelineEmotionHead="Emotion Stated...";
			textTimelineEmotionAction="stated";
			textTimelineHugHead="Ritual performed...";
			textTimelineHugAction="collaborated.";
			textTimelineRitualHead="Hug happened...";
			textTimelineRitualAction="hugged";
			
			textPlayerListHead="Player overview:";	
			textPlayerListLogin="Last Login:";
			textPlayerListEmotion="Last Emotion:";
			textPlayerListHugged="Hugged:";
			
			textCollPage="Your";
			
			textEmotionList="Your usable emotions:";
			textChargePowerPage="State your emotions here:";
			
			textCherryBlossomTreeLevel1="Next blossom state at";
			textCherryBlossomTreeLevel2="points";
			
			textCherryBlossomTreeLevelLast1="Last blossom state at";
			textCherryBlossomTreeLevelLast2="points";
			
		break;
	}
}