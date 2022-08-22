from dotenv import load_dotenv
load_dotenv("../.env")  # take environment variables from .env.

import os
from fastapi import FastAPI
from api.routers import adminRoute
from tortoise.contrib.fastapi import register_tortoise


app = FastAPI()


DATABASE_URL = "mysql://{user_name}:{user_pass}@{mysql_host}:{mysql_port}/{mysql_db_name}".format(
    user_name=os.environ["MYSQL_USER"],
    user_pass=os.environ["MYSQL_PASSWORD"],
    mysql_host=os.environ["MYSQL_HOST"],
    mysql_port=os.environ["MYSQL_PORT"],
    mysql_db_name=os.environ["MYSQL_DATABASE"],
    )


register_tortoise(
    app,
    db_url= DATABASE_URL,
    modules={"models": ["api.models.AdminModel"]},
    generate_schemas=True,
    add_exception_handlers=True
)

@app.get("/api/test")
async def test():
    return {"msg": "Hello World"}


app.include_router(adminRoute.router, prefix="/api/admin", tags=["Admin"])