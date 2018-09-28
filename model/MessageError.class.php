<?php

class MessageError extends Message
{
    const errorDB = "Ошибка базы данных";
    const errorServer = "Ошибка работы сервера";
    const errorAddQuest = "Ошибка добавления вопроса ";
    const errorPostData = "Ошибка полученных данных";
    const errorAddThemeCodeExist = "Нельзя указать для новой темы код существующей";
    const errorAddThemeNotCode = "Не указана новая тема для добавления тестов";
    const errorAddNotText = "Нет текста для добавления";
    const errorAddNotQuests = "Не найдены вопросы для добавления";
    const errorAddCleanTheme = "Ошибка очистки темы ";
    const errorAddNewTheme = "Ошибка добавления темы ";
    const errorAddUpdateTheme = "Ошибка обновления темы ";
    const errorAddAnswer1 = "Ошибка записи ответа ";
    const errorAddAnswer2 = " на вопрос ";
}