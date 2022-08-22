from tortoise import fields, models
from tortoise.contrib.pydantic import pydantic_model_creator
# Tortoise ORM is an easy-to-use asyncio ORM (Object Relational Mapper) inspired by Django.


class AdminTable(models.Model):
    # Here is the list of fields available with custom options of these fields: -
    # https://tortoise-orm.readthedocs.io/en/latest/fields.html#module-tortoise.fields.base
    id = fields.IntField(pk=True)
    username = fields.CharField(max_length=255, null=False)
    email = fields.CharField(max_length=255, null=False, unique=True)
    password = fields.CharField(max_length=255, null=False)
    role = fields.CharField(max_length=255, null=False, default="SUPER")

    class PydanticMeta:
        pass


Admin_Pydantic = pydantic_model_creator(AdminTable, name="Admin")
AdminIn_Pydantic = pydantic_model_creator(AdminTable, name="AdminIn", exclude_readonly=True)
